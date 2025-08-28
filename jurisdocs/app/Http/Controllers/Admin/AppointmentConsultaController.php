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

class AppointmentConsultaController extends Controller
{

    use DatatablTrait;
    private $cliente;
    private $clienteAgenda;

    public function __construct(Cliente $cliente, ClienteController $client)
    {
        $this->cliente = $cliente;
        $this->clienteAgenda = $client;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Gate::denies('appointment_list'))
            return back();


        return view('admin.agendamento.consulta.appointment');
    }




    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
        $agendaReuniao->vc_pataforma = $request->vc_plataforma;
        $agendaReuniao->link_reuniao = $request->vc_link_acesso;
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
        if (!$user->can('appointment_view')) return back();

        $data['appointment'] = Agenda::join('agendamento_consultas AS ac', 'ac.agenda_id', '=', 'agenda.id')
            ->leftJoin('cliente AS cl', 'cl.id', '=', 'agenda.cliente_id')
            ->where('agenda.id', decrypt($id))
            ->select('agenda.*', 'ac.*', 'cl.nome as cliente_nome', 'cl.sobrenome as cliente_sobrenome', 'cl.instituicao as cliente_instituicao')
            ->first();

        return view('admin.agendamento.consulta.appointment_show', $data);
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
            ->select('ac.vc_area as vc_area', 'a.id AS id', 'a.activo AS status', 'a.telefone AS mobile', 'a.data AS date', 'a.nome AS name', 'a.nome AS appointment_name', 'cl.nome AS nome_cliente', 'cl.sobrenome AS sobrenome_cliente', 'cl.instituicao', 'cl.tipo AS tipo_cliente', 'a.cliente_id AS client_id', 'a.type As type', 'a.hora AS time')
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
                $con .= "<option value='CANCEL BY ADVOCA'";
                if ($term->status == 'CANCEL BY ADVOCA') {
                    $con .= "selected";
                }
                $con .= ">Cancelado pelo advogado(a)</option>";


                $con .= "</select>";


                if ($isEdit == "1") {
                    $nestedData['is_active'] = $con;
                } else {
                    $nestedData['is_active'] = "";
                }

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
                $clientName = ($term->tipo_cliente == 2) ? $term->nome_cliente . ' ' . $term->sobrenome_cliente : $term->instituicao;
                $nestedData['name'] = htmlspecialchars($clientName);

                if ($isEdit == "1") {
                    $nestedData['action'] = $this->action([
                        'view' => route('consulta.show', encrypt($term->id)),
                        'edit' => route('consulta.edit', encrypt($term->id)),
                        'edit_permission' => $isEdit,
                    ]);
                } else {
                    $nestedData['action'] = $this->action([
                        'view' => route('consulta.show', encrypt($term->id)),
                    ]);
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
            ->select('agenda.*','ac.vc_area as vc_area', 'ac.vc_tipo', 'ac.vc_pataforma', 'ac.link_reuniao', 'ac.vc_nota', 'ac.it_termo', 'ac.it_envDocs', 'ac.vc_caminho_documento')
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
