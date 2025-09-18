<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests\StoreAppointment;
use App\Http\Controllers\Controller;
use App\Models\{Cliente, AgendamentoReuniao, Agenda, Pais, Provincia, TipoDocumento, TipoPessoa, Factura, PaymentReceived};
use App\Helpers\LogActivity;
use App\Services\ZoomService;
use App\Traits\DatatablTrait;
use Gate;
use Illuminate\Support\Facades\DB;


class AppointmentReuniaoController extends Controller
{

    use DatatablTrait;
    private $cliente;
    private $clienteAgenda;
    private $factura;
    private $pagamento;

    public function __construct(Cliente $cliente, ClienteController $client, PagamentoController $pagamento,  FacturaController $factura)
    {
        $this->cliente = $cliente;
        $this->clienteAgenda = $client;
        $this->factura = $factura;
        $this->pagamento = $pagamento;
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
        $data['advogado_list'] = DB::table('admin AS a')
            ->leftJoin('users AS u', 'a.user_id', '=', 'u.id')
            ->leftJoin('pessoasingular AS p', 'p.id', '=', 'a.pessoasingular_id')
            ->where('user_type', 'ADV')
            ->select('u.*', 'p.nome as nome', 'p.sobrenome as sobrenome')
            ->get();
        return view('admin.agendamento.reuniao.appointment_create', $data);
    }


    public function getMobileno(Request $request)
    {

        $data = $this->cliente->findOrFail($request->id);

        return $data;
    }


    public function store(Agenda $a, Request $request)
    {
        try {
            if ($request->type == "new") {
                $vc_entidade = $request->instituicao
                    ? $request->instituicao
                    : $request->f_name . ' ' . $request->l_name;
            } else {
                $cliente = $this->cliente->where('id', $request->exists_client)->first();
                $vc_entidade = $request->vc_entidade
                    ? $request->vc_entidade
                    : $cliente->full_name;
            }
            $agendaReuniao = AgendamentoReuniao::create([
                'vc_entidade' => $vc_entidade,
                'vc_motivo'   => addslashes($request->vc_motivo),
                'vc_nota'     => addslashes($request->vc_nota),
                'agenda_id'   => $a->id,
                'it_termo'    => $request->it_termo,
            ]);

            if ($agendaReuniao && $request->custo > 0) {
                return $this->processarPagamento($request, $agendaReuniao);
            }
            // return redirect()->route('reuniao.index')->with('success', "Agendamento de reunião criado com sucesso.");
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Erro ao criar agendamento: ' . $e->getMessage()]);
        }
    }

    public function processarPagamento(Request $request, AgendamentoReuniao $a_reuniao)
    {
        $facturaData = [
            'client_id' => $a_reuniao->agenda->cliente_id,
            'agenda_id' => $a_reuniao->agenda_id,
            'inc_Date' => date('Y-m-d'),
            'due_Date' => date('Y-m-d', strtotime('+30 days')),
            'subTotal' => $request->custo,
            'total' => $request->custo,
            'taxVal' => 0,
            'tex_type' => 'none',
            'tax' => 0,
            'note' => 'Factura gerada automaticamente para reunião',
            'invoice_id' => $this->factura->generateInvoice(),
            'invoice_items' => [[
                'description' => 'Reunião - ' . $a_reuniao->vc_motivo,
                'services' => 2,
                'rate' => $request->custo,
                'qty' => 1,
                'amount' => $request->custo
            ]]
        ];
        $facturaRequest = new Request($facturaData);
        $factura = $this->factura->storeFactura($facturaRequest);
 
        if ($factura && $request->comprovativo) {
            return $this->pagamento->registarPagamento($request, $factura->cliente_id, $factura->id);
        }
    }

    public function show($id)
    {
        $user = auth()->user();
        if (!$user->can('appointment_view')) return back();

        $data['appointment'] = Agenda::join('agendamento_reuniaos AS ar', 'ar.agenda_id', '=', 'agenda.id')
            ->leftJoin('cliente AS cl', 'cl.id', '=', 'agenda.cliente_id')
            ->leftJoin('admin AS adm', 'adm.user_id', '=', 'agenda.advogado_id')
            ->leftJoin('pessoasingular AS ps', 'ps.id', '=', 'adm.pessoasingular_id')

            ->where('agenda.id', decrypt($id))
            ->select('agenda.*', 'ar.*', 'cl.nome as cliente_nome', 'cl.sobrenome as cliente_sobrenome', 'cl.instituicao as cliente_instituicao', 'ps.nome as advogado_nome', 'ps.sobrenome as advogado_sobrenome', 'agenda.vc_caminho_pdf')
            ->first();

        return view('admin.agendamento.reuniao.appointment_show', $data);
    }

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

        $totalFiltered = DB::table('agenda AS a')
            ->leftJoin('cliente AS cl', 'cl.id', '=', 'a.cliente_id')
            ->Join('agendamento_reuniaos as ac', 'ac.agenda_id', '=', 'a.id')
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
            ->count();

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
                $con .= ">Remarcado pelo cliente</option>";


                //for CANCEL BY ADVOCATE status
                $con .= "<option value='CANCEL BY ADVOCA'";
                if ($term->status == 'CANCEL BY ADVOCA') {
                    $con .= "selected";
                }
                $con .= ">Remarcado pelo advogado(a)</option>";

                $con .= "<option value='SERVED'";
                if ($term->status == 'SERVED') {
                    $con .= "selected";
                }
                $con .= ">Cliente Atendido(a)</option>";

                $con .= "<option value='TO FORWARD'";

                if ($term->status == 'TO FORWARD') {
                    $con .= "selected";
                }
                $con .= ">Encaminhar para outro advogado</option>";

                $con .= "<option value='PENDING'";
                if ($term->status == 'PENDING') {
                    $con .= "selected";
                }
                $con .= ">Pendente</option>";
                $con .= "</select>";

                if ($isEdit == "1") {
                    $nestedData['is_active'] = $con;
                } else {
                    $nestedData['is_active'] = "";
                }

                $nestedData['id'] = $term->id;
                $nestedData['date'] = date(LogActivity::commonDateFromatType(), strtotime($term->date));
                $nestedData['time'] = date('g:i a', strtotime($term->time));
                $nestedData['mobile'] = htmlspecialchars($term->mobile);
                $nestedData['vc_entidade'] = htmlspecialchars($term->vc_entidade);

                if ($isEdit == "1") {
                    $nestedData['action'] = $this->action([
                        'view' => route('reuniao.show', encrypt($term->id)),
                        'edit' => route('reuniao.edit', encrypt($term->id)),
                        'edit_permission' => $isEdit,
                        'documento' => route('agenda.facturas', encrypt($term->id))
                    ]);
                } else {
                    $nestedData['action'] = $this->action(
                        [
                            'documento' => route('agenda.facturas' . $term->id),
                            'view' => route('reuniao.show', encrypt($term->id)),

                        ]
                    );
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
        if (!$user->can('appointment_edit')) return back();


        $data['appointment'] = Agenda::join('agendamento_reuniaos AS a', 'a.agenda_id', '=', 'agenda.id')
            ->where('agenda.id', decrypt($id))
            ->select('agenda.*', 'a.vc_entidade', 'a.vc_motivo', 'a.vc_nota', 'a.it_termo')
            ->first();
        $data['client_list'] = $this->cliente->where('id',  $data['appointment']->cliente_id)->get();

        return view('admin.agendamento.reuniao.appointment_edit', $data);
    }

    public function update(StoreAppointment $request, $id)
    {

        $agenda = Agenda::findOrFail($id);
        Agenda::where('id', $id)->update([
            'nome' => $request->vc_entidade,
            'telefone' => $request->mobile,
            'data' => date('Y-m-d', strtotime(LogActivity::commonDateFromat($request->date))),
            'hora' => date('H:i:s', strtotime($request->time)),
            'observacao' => $request->vc_nota,
            'email' => $request->email,
            'assunto' => "dd",
            'vc_plataforma' => $request->vc_plataforma,
        ]);

        if (empty($agenda->join_url)) {
            if ($request->vc_plataforma == 'zoom') {
                $zoom = new ZoomService();
                $data = $zoom->createMeeting(
                    $request->vc_tipo ?: 'reunião',
                    "{$request->date}T{$request->time}:00",
                    60
                );
                Agenda::where('id', $id)->update([
                    'join_url' => $data['join_url'],
                    'start_url' => $data['start_url'],
                ]);
            }
        }

        AgendamentoReuniao::where('agenda_id', $id)->update([
            'vc_entidade' => $request->vc_entidade,
            'vc_motivo' => addslashes($request->vc_motivo),
            'vc_nota' => addslashes($request->vc_nota),
            'it_termo' => $request->it_termo ? 1 : 0
        ]);

        return redirect()->route('reuniao.index')->with('success', "Agendamento de reunião atualizado.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {}
}
