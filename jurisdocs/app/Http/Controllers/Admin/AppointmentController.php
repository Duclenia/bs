<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests\StoreAppointment;
use App\Http\Controllers\Controller;
use App\Models\{Cliente, Agenda, Pais, Provincia, TipoDocumento, TipoPessoa};
use App\Helpers\LogActivity;

use App\Services\ZoomService;
use App\Traits\DatatablTrait;
use Gate;
use DB;

class AppointmentController extends Controller
{

    use DatatablTrait;
    private $cliente;
    private $agendaReuniao;
    private $agendaConsulta;
    private $clienteAgenda;


    public function __construct(
        Cliente $cliente,
        ClienteController $client,
        AppointmentReuniaoController $ar,
        AppointmentConsultaController $ac

    ) {
        $this->cliente = $cliente;
        $this->agendaReuniao = $ar;
        $this->agendaConsulta = $ac;
        $this->clienteAgenda = $client;
    }

    public function index()
    {
        if (Gate::denies('appointment_list'))
            return back();
        return view('admin.appointment.appointment');
    }

    public function create()
    {
        if (Gate::denies('appointment_add'))
            return back();

        $data['client_list'] = $this->cliente->where('activo', 'S')->get();
        $data['country'] = Pais::all();
        $data['state'] = Provincia::all();
        $data['tipospessoas'] = TipoPessoa::all();
        $data['tiposdocumentos'] = TipoDocumento::all();

        return view('admin.agendamento.reuniao.appointment_create', $data);
    }

    public function create_consulta()
    {
        if (Gate::denies('appointment_add'))
            return back();

        $data['client_list'] = $this->cliente->where('activo', 'S')->get();
        $data['country'] = Pais::all();
        $data['state'] = Provincia::all();
        $data['tipospessoas'] = TipoPessoa::all();
        $data['tiposdocumentos'] = TipoDocumento::all();

        return view('admin.agendamento.consulta.appointment_create', $data);
    }

    public function getMobileno(Request $request)
    {
        $data = $this->cliente->findOrFail($request->id);
        return $data;
    }

    public function store(StoreAppointment $request)
    {
        try {

            $agenda = $this->criarAgenda($request);

            if ($request->type_agenda == "reuniao") {
                return $this->agendaReuniao->store($agenda, $request);
            } else {
                return  $this->agendaConsulta->store($agenda, $request);
            }

            //Mail::to($request->email)->send(new MeetingCreatedMail($agenda));
        } catch (\Exception $e) {

            return back()->with('error', 'Erro ao criar agenda.');
        }
    }

    private function criarAgenda(StoreAppointment $request): Agenda
    {

        $agenda = new Agenda();
        $agenda->cliente_id = $request->type == "new"  ? $this->clienteAgenda->storeCliente($request)
            : $request->exists_client;
        $agenda->email = $request->email;
        $agenda->telefone = $request->mobile;
        $agenda->data = date('Y-m-d H:i:s', strtotime(LogActivity::commonDateFromat($request->date)));
        $agenda->hora = date('H:i:s', strtotime($request->time));
        $agenda->observacao = addslashes($request->vc_nota);
        $agenda->type = $request->type;
        $agenda->advogado_id = $request->advogado_id;
        $agenda->custo = $request->custo;
        $agenda->vc_plataforma = $request->vc_plataforma;

        if ($request->vc_plataforma == 'zoom') {
            $zoom = new ZoomService();
            $data = $zoom->createMeeting($request->vc_tipo ?: 'reunião', "{$request->date}T{$request->time}:00", 60);
            $agenda->join_url = $data['join_url'];
            $agenda->start_url = $data['start_url'];
        }

        $agenda->save();
        return $agenda;
    }

    public function show($id) {}

    public function appointmentList(Request $request)
    {

        $user = auth()->user();
        $isEdit = $user->can('appointment_edit');

        $columns = array(
            0 => 'id',
            1 => 'name',
            2 => 'date',
            3 => 'time',
            4 => 'mobile',
            5 => 'is_active'

        );

        $totalData = DB::table('agenda AS a')
            ->leftJoin('cliente AS cl', 'cl.id', '=', 'a.cliente_id')
            ->count();

        $totalRec = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $search = $request->input('search.value');

        $terms = DB::table('agenda AS a')
            ->leftJoin('cliente AS cl', 'cl.id', '=', 'a.cliente_id')
            ->select('a.id AS id', 'a.activo AS status', 'a.telefone AS mobile', 'a.data AS date', 'a.nome AS name', 'a.nome AS appointment_name', 'cl.nome AS nome_cliente', 'cl.sobrenome AS sobrenome_cliente', 'cl.instituicao', 'cl.tipo AS tipo_cliente', 'a.cliente_id AS client_id', 'a.type As type', 'a.hora AS time')
            ->when($request->input('appoint_date_from'), function ($query, $iterm) {
                $iterm = LogActivity::commonDateFromat($iterm);
                return $query->whereDate('a.data', '>=', date('Y-m-d', strtotime($iterm)));
            })
            ->when($request->input('appoint_date_to'), function ($query, $iterm) {
                $iterm = LogActivity::commonDateFromat($iterm);
                return $query->whereDate('a.data', '<=', date('Y-m-d', strtotime($iterm)));
            })
            ->where(function ($query) use ($search) {
                return $query->where('a.telefone', 'LIKE', "%{$search}%")
                    ->orWhere('a.nome', 'LIKE', "%{$search}%")
                    ->orWhere('cl.nome', 'LIKE', "%{$search}%")
                    ->orWhere('cl.sobrenome', 'LIKE', "%{$search}%")
                    ->orWhere('cl.instituicao', 'LIKE', "%{$search}%")
                    ->orWhere('a.activo', 'LIKE', "%{$search}%");
            })
            ->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();

        $totalFiltered = $terms->count();

        $data = array();
        if (!empty($terms)) {

            foreach ($terms as $term) {
                $edit = route('agenda.edit', $term->id);
                $token = csrf_field();

                $action_delete = route('agenda.destroy', $term->id);

                $delete = "<form action='{$action_delete}' method='post' onsubmit ='return  confirmDelete()'>
                {$token}
                            <input name='_method' type='hidden' value='DELETE'>
                            <button class='dropdown-item text-center' type='submit'  style='background: transparent;
    border: none;'>DELETE</button>
                          </form>";

                $con = '<select name="status" class="appointment-select2" id="status" onchange="change_status(' . "'" . $term->id . "'" . ',' . 'getval(this)' . ',' . "'" . 'agenda' . "'" . ')">';

                //for open status
                $con .= "<option value='OPEN'";
                if ($term->status == 'OPEN') {
                    $con .= "selected";
                }
                $con .= ">Aberto</option>";

                //for CANCEL BY CLIENT status

                $con .= "<option value='CANCEL BY CLIENT'";
                if ($term->status == 'CANCEL BY CLIENT') {
                    $con .= "selected";
                }
                $con .= ">Cancelado pelo cliente</option>";


                //for CANCEL BY ADVOCATE status
                $con .= "<option value='CANCEL BY ADVOCATE'";
                if ($term->status == 'CANCEL BY ADVOCATE') {
                    $con .= "selected";
                }

                $con .= "</select>";


                if ($isEdit == "1") {
                    $nestedData['is_active'] = $con;
                } else {
                    $nestedData['is_active'] = "";
                }

                if (empty($request->input('search.value'))) {
                    $final = $totalRec - $start;
                    $nestedData['id'] = $final;
                    $totalRec--;
                } else {
                    $start++;
                    $nestedData['id'] = $start;
                }
                $nestedData['date'] = date(LogActivity::commonDateFromatType(), strtotime($term->date));
                $nestedData['time'] = date('g:i a', strtotime($term->time));


                $nestedData['mobile'] = htmlspecialchars($term->mobile);
                if ($term->type == "new") {
                    $nestedData['name'] = htmlspecialchars($term->appointment_name);
                } else {

                    $clientName = ($term->tipo_cliente == 2) ? $term->nome_cliente . ' ' . $term->sobrenome_cliente : $term->instituicao;

                    $nestedData['name'] = htmlspecialchars($clientName);
                }

                if ($isEdit == "1") {
                    $nestedData['action'] = $this->action([
                        'edit' => route('agenda.edit', encrypt($term->id)),
                        'edit_permission' => $isEdit,
                    ]);
                } else {
                    $nestedData['action'] = [];
                }

                $data[] = $nestedData;
            }
        }

        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );

        echo json_encode($json_data);
    }

    public function edit($id)
    {

        $user = auth()->user();
        if (!$user->can('appointment_edit'))
            return back();

        $data['client_list'] = $this->cliente->where('activo', 'S')->get();
        $data['appointment'] = Agenda::findOrFail(decrypt($id));

        return view('admin.appointment.appointment_edit', $data);
    }

    public function update(StoreAppointment $request, $id)
    {

        $agenda = Agenda::findOrFail($id);

        if ($request->type == "new")
            $agenda->nome = $request->new_client;
        else
            $agenda->cliente_id = $request->exists_client;

        Agenda::findOrFail('id', $id)->update([
            'assunto' => $request->assunto,
            'telefone' => $request->mobile,
            'data' => date('Y-m-d H:i', strtotime(LogActivity::commonDateFromat($request->date))),
            'hora' => date('H:i:s', strtotime($request->time)),
            'observacao' => $request->note,

        ]);

        return redirect()->route('agenda.index')->with('success', "Agenda actualizada.");
    }

    public function checkClientEmailExists(Request $request)
    {
        $count = DB::table('users')->where('email', $request->email)->count();

        return response()->json([
            'exists' => $count > 0
        ]);
    }

    public function destroy($id) {}

    // encaminhar agendamento para outro advogado
    public function encaminharAgendamento(Request $request)
    {
        try {
            DB::beginTransaction();
            $agenda = Agenda::findOrFail($request->agendamento_id);
            $agenda->update([
                'advogado_id' => $request->novo_advogado_id,
                'data' => $request->nova_data,
                'hora' => $request->novo_horario,
                'activo' => 'TO FORWARD'
            ]);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Consulta encaminhada com sucesso!'
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Erro ao encaminhar consulta: ' . $request
            ], 500);
        }
    }

    public function uploadComprovativo(Request $request)
    {
        $request->validate([
            'agendamento_id' => 'required|exists:agenda,id',
            'comprovativo' => 'required|file|mimes:pdf|max:10240'
        ]);

        try {
            $file = $request->file('comprovativo');
            $path = $file->store('agendamento/comprovativos', 'public');

            Agenda::where('id', $request->agendamento_id)->update([
                'vc_caminho_pdf' => $path
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Comprovativo enviado com sucesso!',
                'path' => $path
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao enviar comprovativo: ' . $e->getMessage()
            ], 500);
        }
    }
}
