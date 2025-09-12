<?php

namespace App\Http\Controllers\Cliente;

use App\AgendamentoConsulta;
use App\AgendamentoReuniao;
use Illuminate\Http\Request;
use App\Http\Requests\StoreAppointment;
use App\Http\Controllers\Controller;
use App\Models\{Cliente, Agenda, Pais, Provincia, TipoDocumento, TipoPessoa};
use App\Helpers\LogActivity;
use App\Http\Controllers\Admin\ClienteController;
use App\Http\Requests\StoreClient;
use App\Notifications\ActivityNotification;
use Illuminate\Support\Facades\Notification;
use App\Traits\DatatablTrait;

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

        return view('cliente.appointment.reuniao.appointment');
    }

    public function create()
    {
        $cliente = auth()->user()->cliente;

        if ($cliente->activo == 'S') {
            // Buscar primeiro advogado que o cliente já tem vinculação
            $advogadoCliente = DB::table('agenda')
                ->where('cliente_id', $cliente->id)
                ->whereNotNull('advogado_id')
                ->orderBy('id', 'desc')
                ->value('advogado_id');

            $data['advogado_list'] = DB::table('admin AS a')
                ->leftJoin('users AS u', 'a.user_id', '=', 'u.id')
                ->leftJoin('pessoasingular AS p', 'p.id', '=', 'a.pessoasingular_id')
                ->where('user_type', 'ADV')
                ->select('u.*', 'p.nome as nome', 'p.sobrenome as sobrenome')
                ->get();

            $data['advogado_selecionado'] = $advogadoCliente;

            return view('cliente.appointment.reuniao.appointment_create', $data);
        } else
            return back();
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
        $agendaReuniao->vc_nota = addslashes($request->vc_nota);
        $agendaReuniao->agenda_id = $a->id;
        $agendaReuniao->it_termo = $request->it_termo;
        $agendaReuniao->save();
        return redirect()->route('reuniao.index')->with('success', "Agendamento de reunião criado.");
    }


    public function show($id)
    {
        $user = auth()->user();
        $cliente = $user->cliente;

        $data['appointment'] = Agenda::join('agendamento_reuniaos AS ar', 'ar.agenda_id', '=', 'agenda.id')
            ->leftJoin('cliente AS cl', 'cl.id', '=', 'agenda.cliente_id')
            ->leftJoin('admin AS adm', 'adm.user_id', '=', 'agenda.advogado_id')
            ->leftJoin('pessoasingular AS ps', 'ps.id', '=', 'adm.pessoasingular_id')

            ->where('agenda.id', decrypt($id))
            ->where('agenda.cliente_id', $cliente->id)
            ->select('agenda.*', 'ar.*', 'ps.*')
            ->first();

        if (!$data['appointment']) {
            return back()->with('error', 'Reunião não encontrada.');
        }

        return view('cliente.appointment.reuniao.appointment_show', $data);
    }

    public function appointmentList(Request $request)
    {

        $user = auth()->user();
        $isEdit = $user->can('appointment_edit');

        $cliente = $user->cliente;

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
            ->select('ac.vc_entidade as vc_entidade', 'a.id AS id', 'a.activo AS status', 'a.telefone AS mobile', 'a.data AS date', 'a.nome AS name', 'a.nome AS appointment_name', 'cl.nome AS nome_cliente', 'cl.sobrenome AS sobrenome_cliente', 'cl.instituicao', 'cl.tipo AS tipo_cliente', 'a.cliente_id AS client_id', 'a.type As type', 'a.custo AS custo')
            ->where('a.cliente_id', $cliente->id)
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
            ->select('ac.vc_entidade as vc_entidade', 'a.id AS id', 'a.activo AS status', 'a.telefone AS mobile', 'a.data AS date', 'a.nome AS name', 'a.nome AS appointment_name', 'cl.nome AS nome_cliente', 'cl.sobrenome AS sobrenome_cliente', 'cl.instituicao', 'cl.tipo AS tipo_cliente', 'a.cliente_id AS client_id', 'a.type As type', 'a.hora AS time', 'a.vc_caminho_pdf')
            ->orderBy('ac.id', 'ASC')
            ->where('a.cliente_id', $cliente->id)
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
            ->where('a.cliente_id', $cliente->id)
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


                // Cliente só pode ver o estado, não alterar
                if ($term->status == 'OPEN') {
                    $statusText = 'Aberto';
                } elseif ($term->status == 'CANCEL BY CLIENT') {
                    $statusText = 'Cancelado pelo cliente';
                } elseif ($term->status == 'CANCEL BY ADVOCATE') {
                    $statusText = 'Cancelado pelo advogado';
                } elseif ($term->status == 'TO FORWARD') {
                    $statusText = 'Encaminhado para advogado';
                } elseif ($term->status == 'PENDING') {
                    $statusText = 'Pendente, por aprovar';
                } else {
                    $statusText = $term->status;
                }

                $nestedData['is_active'] = $statusText;

                if (empty($request->input('search.value'))) {
                    $final = $totalRec - $start;
                    $nestedData['id'] = $final;
                    $totalRec--;
                } else {
                    $start++;
                    $nestedData['id'] = $term->id;
                }
                $nestedData['date'] = date(LogActivity::commonDateFromatType(), strtotime($term->date));
                $nestedData['time'] = date('g:i a', strtotime($term->time));
                $nestedData['mobile'] = htmlspecialchars($term->mobile);
                $nestedData['vc_entidade'] = htmlspecialchars($term->vc_entidade);

                $actionData = [
                    'view' => route('cliente.reuniao.show', encrypt($term->id)),
                    'documento' => route('agenda.facturas', encrypt($term->id))
                ];

                $nestedData['action'] = $this->action($actionData);

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
