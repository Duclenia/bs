<?php

namespace App\Http\Controllers\Admin;

use App\AgendamentoConsulta;
use App\AgendamentoReuniao;
use Illuminate\Http\Request;
use App\Http\Requests\StoreAppointment;
use App\Http\Controllers\Controller;
use App\Models\{Cliente, Agenda, Pais, Provincia, TipoDocumento, TipoPessoa};
use App\Helpers\LogActivity;
use App\Http\Requests\StoreClient;
use App\Notifications\ActivityNotification;
use Illuminate\Support\Facades\Notification;
use App\Traits\DatatablTrait;
use Gate;
use DB;

use function PHPSTORM_META\type;

class AppointmentReuniaoController extends Controller
{

    use DatatablTrait;
    private $cliente;
    private $clienteAgenda;

    public function __construct(Cliente $cliente, ClienteController $client)
    {
        $this->cliente = $cliente;
        $this->clienteAgenda = $client;
    }


    public function index()
    {
        if (Gate::denies('appointment_list'))
            return back();


        return view('admin.agendamento.reuniao.appointment');
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


    public function getMobileno(Request $request)
    {

        $data = $this->cliente->findOrFail($request->id);

        return $data;
    }


    public function store(Agenda $a, StoreAppointment $request)
    {

        $agendaReuniao = new AgendamentoReuniao();
        if ($request->type == "new") {
            $agendaReuniao->vc_entidade = ($request->instituicao) ? $request->instituicao : $request->nome . ' ' . $request->sobrenome;
        } else {

            $cliente = $this->cliente->where('id', $request->exists_client)->first();
            $agendaReuniao->vc_entidade = ($request->vc_entidade) ? $request->vc_entidade : $cliente->full_name;
        }
        $agendaReuniao->vc_motivo = addslashes($request->vc_motivo);
        $agendaReuniao->vc_pataforma = $request->vc_plataforma;
        $agendaReuniao->link_reuniao = $request->vc_link_acesso;
        $agendaReuniao->vc_nota = addslashes($request->vc_nota);
        $agendaReuniao->agenda_id = $a->id;
        $agendaReuniao->it_termo = $request->it_termo;
        $agendaReuniao->save();
        return redirect()->route('reuniao.index')->with('success', "Agendamento de reuni達o criado.");
    }


    public function show($id) {}

    public function appointmentList(Request $request)
    {

        $user = auth()->user();
        $isEdit = $user->can('appointment_edit');

        /*
          |----------------
          | Listing colomns
          |----------------
         */
        $columns = array(
            0 => 'id',
            1 => 'vc_entidade',
            2 => 'date',
            3 => 'time',
            4 => 'mobile',
            5 => 'is_active',

        );

        $totalData = DB::table('agenda AS a')
            ->leftJoin('cliente AS cl', 'cl.id', '=', 'a.cliente_id')
            ->Join('agendamento_reuniaos as ac', 'ac.agenda_id', '=', 'a.id')
            ->select('ac.vc_entidade as vc_entidade', 'a.id AS id', 'a.activo AS status', 'a.telefone AS mobile', 'a.data AS date', 'a.nome AS name', 'a.nome AS appointment_name', 'cl.nome AS nome_cliente', 'cl.sobrenome AS sobrenome_cliente', 'cl.instituicao', 'cl.tipo AS tipo_cliente', 'a.cliente_id AS client_id', 'a.type As type')
            ->count();

        $totalRec = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $search = $request->input('search.value');

        $terms = DB::table('agenda AS a')
            ->leftJoin('cliente AS cl', 'cl.id', '=', 'a.cliente_id')
            ->Join('agendamento_reuniaos as ac', 'ac.agenda_id', '=', 'a.id')
            ->select('ac.vc_entidade as vc_entidade', 'a.id AS id', 'a.activo AS status', 'a.telefone AS mobile', 'a.data AS date', 'a.nome AS name', 'a.nome AS appointment_name', 'cl.nome AS nome_cliente', 'cl.sobrenome AS sobrenome_cliente', 'cl.instituicao', 'cl.tipo AS tipo_cliente', 'a.cliente_id AS client_id', 'a.type As type', 'a.hora AS time')
            ->orderBy('id', 'DESC')
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
                    ->orWhere('ac.vc_entidade', 'LIKE', "%{$search}%")
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

                $edit = route('reuniao.edit', $term->id);
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
                $con .= ">Cancelado pelo advogado(a)</option>";


                $con .= "</select>";


                if ($isEdit == "1") {
                    $nestedData['is_active'] = $con;
                } else {
                    $nestedData['is_active'] = "";
                }

                // if (empty($request->input('search.value'))) {
                //     $final = $totalRec - $start;
                //     $nestedData['id'] = $final;
                //     $totalRec--;
                //      \Log::debug("message". $nestedData['id'] );
                // } else {
                //     $start++;
                //     $nestedData['id'] = $term->id;
                //      \Log::debug("message_id2". $nestedData['id'] );
                // }
                $nestedData['id'] = $term->id;
                $nestedData['date'] = date(LogActivity::commonDateFromatType(), strtotime($term->date));
                $nestedData['time'] = date('g:i a', strtotime($term->time));
                $nestedData['mobile'] = htmlspecialchars($term->mobile);
                $nestedData['vc_entidade'] = htmlspecialchars($term->vc_entidade);

                if ($isEdit == "1") {
                    $nestedData['action'] = $this->action([
                        'edit' => route('reuniao.edit', encrypt($term->id)),
                        'edit_permission' => $isEdit,
                    ]);
                } else {
                    $nestedData['action'] = [];
                }
 \Log::debug("DATA".json_encode($nestedData['id']));
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
        if (!$user->can('appointment_edit')) return back();


        $data['appointment'] = Agenda::join('agendamento_reuniaos AS a', 'a.agenda_id', '=', 'agenda.id')
            ->where('agenda.id', decrypt($id))
            ->select('agenda.*', 'a.vc_entidade', 'a.vc_motivo', 'a.vc_pataforma', 'a.link_reuniao', 'a.vc_nota', 'a.it_termo')
            ->first();
        $data['client_list'] = $this->cliente->where('id',  $data['appointment']->cliente_id)->get();

        return view('admin.agendamento.reuniao.appointment_edit', $data);
    }

    public function update(StoreAppointment $request, $id)
    {
        $agenda = Agenda::findOrFail($id);
        $agenda->update([
            'assunto' => addslashes($request->assunto),
            'telefone' => $request->mobile,
            'data' => date('Y-m-d H:i', strtotime(LogActivity::commonDateFromat($request->date))),
            'hora' => date('H:i:s', strtotime($request->time)),
            'observacao' => $request->note,
        ]);

        AgendamentoReuniao::where('agenda_id', $id)->update([
            'vc_entidade' => $request->vc_entidade,
            'vc_motivo' => addslashes($request->vc_motivo),
            'vc_pataforma' => $request->vc_plataforma,
            'link_reuniao' => $request->vc_link_acesso,
            'vc_nota' => addslashes($request->vc_nota),
            'it_termo' => $request->it_termo
        ]);
        return redirect()->route('reuniao.index')->with('success', "Agendamento de reuni達o atualizado.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {}
}
