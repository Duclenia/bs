<?php

namespace App\Http\Controllers\Cliente;

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

class AppointmentConsultaController extends Controller
{

    use DatatablTrait;
    private $cliente;
    private $clienteAgenda;

    public function __construct(Cliente $cliente)
    {
        $this->cliente = $cliente;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cliente = auth()->user()->cliente;

        if ($cliente->activo == 'S')
            return view('cliente.appointment.consulta.appointment');
        else
            return back();
    }




    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cliente = auth()->user()->cliente;

        if ($cliente->activo == 'S') {
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
            return view('cliente.appointment.consulta.appointment_create', $data);
        } else
            return back();
    }


    public function getMobileno(Request $request)
    {
        $data = $this->cliente->with('utilizador')->findOrFail($request->id);
        return $data;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreAppointment $request
     * @return \Illuminate\Http\Response
     */




    public function store(Agenda $a, StoreAppointment $request)
    {

        $agendaReuniao = new AgendamentoConsulta();
        $agendaReuniao->vc_tipo = $request->vc_tipo;
        $agendaReuniao->vc_area = $request->vc_area ? $request->vc_area : $request->vc_area_outro;
        $agendaReuniao->vc_nota = addslashes($request->vc_nota);
        $agendaReuniao->agenda_id = $a->id;
        $agendaReuniao->it_termo = $request->it_termo;
        $agendaReuniao->it_envDocs = $request->it_envDocs ? $request->it_envDocs : 0;
        $agendaReuniao->vc_caminho_documento = $request->vc_doc;
        $agendaReuniao->save();
        return redirect()->route('consulta.index')->with('success', "Agendamento de consulta criado.");
    }

    public function show($id)
    {
        $user = auth()->user();
        $cliente = $user->cliente;

        $data['appointment'] = Agenda::join('agendamento_consultas AS ac', 'ac.agenda_id', '=', 'agenda.id')
            ->leftJoin('admin AS adm', 'adm.user_id', '=', 'agenda.advogado_id')
            ->leftJoin('pessoasingular AS ps', 'ps.id', '=', 'adm.pessoasingular_id')

            ->where('agenda.id', decrypt($id))
            ->where('agenda.cliente_id', $cliente->id)
            ->select('agenda.*', 'ac.*', 'ps.*')
            ->first();

        if (!$data['appointment']) {
            return back()->with('error', 'Consulta não encontrada.');
        }

        return view('cliente.appointment.consulta.appointment_show', $data);
    }

    public function appointmentList(Request $request)
    {

        $user = auth()->user();
        $cliente = $user->cliente;

        /*
          |----------------
          | Listing colomns
          |----------------
         */
        $columns = array(
            0 => 'id',
            1 => 'name',
            2 => 'date',
            3 => 'time',
            4 => 'mobile',
            5 => 'is_active',
            6 => 'vc_area',
        );

        $totalData = DB::table('agenda AS a')
            ->leftJoin('cliente AS cl', 'cl.id', '=', 'a.cliente_id')
            ->Join('agendamento_consultas as ac', 'ac.agenda_id', '=', 'a.id')
            ->select('ac.vc_area as vc_area', 'a.id AS id', 'a.activo AS status', 'a.telefone AS mobile', 'a.data AS date', 'a.nome AS name', 'a.nome AS appointment_name', 'cl.nome AS nome_cliente', 'cl.sobrenome AS sobrenome_cliente', 'cl.instituicao', 'cl.tipo AS tipo_cliente', 'a.cliente_id AS client_id', 'a.type As type')
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
            ->Join('agendamento_consultas as ac', 'ac.agenda_id', '=', 'a.id')
            ->leftJoin('admin AS adm', 'adm.user_id', '=', 'a.advogado_id')
            ->leftJoin('pessoasingular AS ps', 'ps.id', '=', 'adm.pessoasingular_id')
            ->select('ac.vc_tipo as vc_tipo', 'ac.vc_area as vc_area', 'a.id AS id', 'a.activo AS status', 'a.telefone AS mobile', 'a.data AS date', 'a.nome AS appointment_name', 'cl.nome AS nome_cliente', 'cl.sobrenome AS sobrenome_cliente', 'cl.instituicao', 'cl.tipo AS tipo_cliente', 'a.cliente_id AS client_id', 'a.type As type', 'a.hora AS time', 'a.vc_caminho_pdf', 'ps.nome as advogado_nome', 'ps.sobrenome as advogado_sobrenome')
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
                    ->orWhere('ac.vc_area', 'LIKE', "%{$search}%")
                    ->orWhere('a.activo', 'LIKE', "%{$search}%");
            })
            ->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();

        /*
          |--------------------------------------------
          | For table search filter from frontend site inside two table namely courses and courseterms.
          |--------------------------------------------
         */

        /*
          |----------------------------------------------------------------------------------------------------------------------------------
          | Creating json array with all records based on input from front end site like all,searcheded,pagination record (i.e 10,20,50,100).
          |----------------------------------------------------------------------------------------------------------------------------------
         */

        $totalFiltered = DB::table('agenda AS a')
            ->leftJoin('cliente AS cl', 'cl.id', '=', 'a.cliente_id')
            ->Join('agendamento_consultas as ac', 'ac.agenda_id', '=', 'a.id')
            ->select('ac.vc_area as vc_area', 'a.id AS id', 'a.activo AS status', 'a.telefone AS mobile', 'a.data AS date', 'a.nome AS name', 'a.nome AS appointment_name', 'cl.nome AS nome_cliente', 'cl.sobrenome AS sobrenome_cliente', 'cl.instituicao', 'cl.tipo AS tipo_cliente', 'a.cliente_id AS client_id', 'a.type As type', 'a.hora AS time')
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
                    ->orWhere('ac.vc_area', 'LIKE', "%{$search}%")
                    ->orWhere('a.activo', 'LIKE', "%{$search}%");
            })
            ->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->count();

        $data = array();
        if (!empty($terms)) {

            foreach ($terms as $term) {

                /**
                 * For HTMl action option like edit and delete
                 */
                $edit = route('agenda.edit', $term->id);
                $token = csrf_field();

                // $action_delete = '"'.route('sale-Admin.destroy', $cat->id).'"';
                $action_delete = route('agenda.destroy', $term->id);

                $delete = "<form action='{$action_delete}' method='post' onsubmit ='return  confirmDelete()'>
                {$token}
                            <input name='_method' type='hidden' value='DELETE'>
                            <button class='dropdown-item text-center' type='submit'  style='background: transparent;
    border: none;'>DELETE</button>
                          </form>";

                /**
                 * -/End
                 */
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

                /*  if (empty($request->input('search.value'))) {
                    $final = $totalRec - $start;
                    $nestedData['id'] = $final;
                    $totalRec--;
                } else {
                    $start++;
                    $nestedData['id'] = $start;
                } */
                $nestedData['id'] = $term->id;
                $nestedData['date'] = date(LogActivity::commonDateFromatType(), strtotime($term->date));
                $nestedData['time'] = date('g:i a', strtotime($term->time));


                $nestedData['mobile'] = htmlspecialchars($term->mobile);
                $nestedData['vc_area'] = htmlspecialchars($term->vc_area);
                $nestedData['name'] = htmlspecialchars($term->vc_tipo);

                $actionData = [
                    'view' => route('cliente.consulta.show', encrypt($term->id))
                ];

                // Só mostrar upload se não tiver comprovativo
                if (empty($term->vc_caminho_pdf)) {
                    $actionData['upload_comprovativo'] = collect(['id' => $term->id]);
                }

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

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = auth()->user();
        if (!$user->can('appointment_edit'))
            return back();

        $data['appointment'] = Agenda::join('agendamento_consultas AS ac', 'ac.agenda_id', '=', 'agenda.id')
            ->where('agenda.id', decrypt($id))
            ->select('agenda.*', 'ac.vc_area as vc_area', 'ac.vc_tipo', 'ac.vc_pataforma', 'ac.link_reuniao', 'ac.vc_nota', 'ac.it_termo', 'ac.it_envDocs', 'ac.vc_caminho_documento')
            ->first();
        $data['client_list'] = $this->cliente->where('id',  $data['appointment']->cliente_id)->get();

        return view('admin.agendamento.consulta.appointment_edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\StoreAppointment $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
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

        AgendamentoConsulta::where('agenda_id', $id)->update([
            'vc_tipo' => $request->vc_tipo,
            'vc_area' => addslashes($request->vc_area),
            'vc_pataforma' => $request->vc_plataforma,
            'link_reuniao' => $request->vc_link_acesso,
            'vc_nota' => addslashes($request->vc_nota),
            'it_termo' => $request->it_termo,
            'it_envDocs' => $request->it_envDocs,
            'vc_caminho_documento' => $request->vc_doc
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
