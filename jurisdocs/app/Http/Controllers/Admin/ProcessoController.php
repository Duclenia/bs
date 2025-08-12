<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Gate;
use App\Http\Controllers\Controller;
use App\Models\EstadoProcesso;
use App\Models\{Juiz,Processo,HistoricoProcesso,CaseTransfer};
use App\Models\{Cliente,ProcessoMembro,ParteContraria,ClienteParte};
use App\Models\ConfiguracaoGeral;
use App\Models\Admin;
use App\Models\AreaProcessual;
use App\Models\IntervenienteDesignacao;
use App\Http\Requests\{StoreProcessoRequest,UpdateProcessoRequest};
use Validator;
use DB;
// use pdf;
use PDF;
use App\Traits\DatatablTrait;
use App\Helpers\LogActivity;

class ProcessoController extends Controller
{

    use DatatablTrait;

    private $processo;
    private $admin;
    private $cliente;
    private $areaprocessual;

    public function __construct(Processo $processo, Admin $admin, AreaProcessual $areaprocessual, Cliente $cliente) {

        $this->processo = $processo;
        $this->admin = $admin;
        $this->areaprocessual = $areaprocessual;
        $this->cliente = $cliente;
    }

    public function select2Case(Request $request)
    {
        $search = $request->get('search');

        $data = DB::table('processo AS p')
                ->leftJoin('cliente AS cl', 'cl.id', '=', 'p.cliente_id')
                ->leftJoin('tipoprocesso AS ct', 'ct.id', '=', 'p.tipoprocesso_id')
                ->leftJoin('estadoprocesso AS s', 's.id', '=', 'p.estado')
                ->leftJoin('tribunal AS c', 'c.id', '=', 'p.tribunal_id')
                ->leftJoin('juiz AS j', 'j.id', '=', 'p.juiz_id')
                ->select('p.id AS id', 'p.no_interno', 'p.no_processo', 's.estado', 'cl.nome', 'cl.sobrenome', 'cl.instituicao', 'cl.tipo', 'p.updated_by', 'cl.id AS advo_client_id', 'p.activo'
                )
                // ->where('case.is_active','Yes')
                ->where('cl.nome', 'like', '%' . $search . '%')
                ->orWhere('cl.sobrenome', 'like', '%' . $search . '%')
                ->orWhere('cl.instituicao', 'like', '%' . $search . '%')
                ->get();

        return response()->json(['items' => $data->toArray(), 'pagination' => false]);
    }

    public function index()
    {
        
        if (Gate::denies('case_list'))
            return back();

        $totalData = DB::table('processo AS p')
                ->leftJoin('cliente AS cl', 'cl.id', '=', 'p.cliente_id')
                ->leftJoin('tipoprocesso AS tp', 'p.tipoprocesso_id', '=', 'tp.id')
                ->leftJoin('estadoprocesso AS s', 's.id', '=', 'p.estado')
                ->leftJoin('tribunal AS tb', 'p.tribunal_id', '=', 'tb.id')
                ->leftJoin('juiz AS j', 'p.juiz_id', '=', 'j.id')
                ->select('p.id AS case_id', 'p.client_position', 'p.party_name', 'p.party_lawyer', 'p.no_processo', 'j.nome', 'tp.designacao AS caseType', 's.estado', 'tb.nome', 'j.nome', 'cl.nome', 'cl.sobrenome', 'cl.instituicao', 'p.updated_by', 'cl.id AS advo_client_id'
                )
                ->count();

        $areasprocessuais = $this->areaprocessual->all();

        return view('admin.processo.running', compact('areasprocessuais'));
    }

    public function caseImportant()
    {
        $user = auth()->user();
        if (!$user->can('case_list'))
            return back();

        return view('admin.processo.important_cases');
    }

    public function caseNB()
    {
        $user = auth()->user();
        if (!$user->can('case_list'))
            return back();

        return view('admin.processo.nb-cases');
    }

    public function caseArchived()
    {
        $user = auth()->user();
        if (!$user->can('case_list'))
            return back();

        return view('admin.processo.archived');
    }

    public function caseListByClientId($id)
    {
        $user = auth()->user();
        
        if (!$user->can('case_list'))
            return back();

        $cliente = $this->cliente->with('processos')->findOrFail(decrypt($id));

        return view('admin.processo.client_case_list', compact('cliente'));
    }

    public function client_case_list(Request $request)
    {

        $user = auth()->user();
        $isEdit = $user->can('case_edit');
        $isDelete = $user->can('case_delete');
        /*
          |----------------
          | Listing colomns
          |----------------
         */

        $columns = array(
            0 => 'case_id',
            1 => 'first_name',
            5 => 'last_login_at',
            6 => 'is_active'
        );


        $cond = array('p.cliente_id' => $request->advocate_client_id);

        $totalData = DB::table('processo AS p')
                ->leftJoin('cliente AS cl', 'cl.id', '=', 'p.cliente_id')
                ->leftJoin('tipoprocesso AS ct', 'ct.id', '=', 'p.tipoprocesso_id')
                ->leftJoin('estadoprocesso AS s', 's.id', '=', 'p.estado')
                ->leftJoin('tribunal AS tb', 'tb.id', '=', 'p.tribunal_id')
                ->leftJoin('juiz AS j', 'j.id', '=', 'p.juiz_id')
                ->leftJoin('areaprocessual AS ap', 'p.areaprocessual_id', '=', 'ap.id')
                ->select('p.id AS case_id', 'ap.designacao AS areaprocessual', 'p.no_processo', 'p.client_position', 'p.party_name', 'p.party_lawyer', 'ct.designacao AS caseType', 's.estado', 'tb.nome AS tribunal', 'j.nome AS juiz', 'cl.nome', 'cl.sobrenome', 'cl.instituicao', 'p.updated_by', 'cl.id AS advo_client_id'
                )
                ->where($cond)
                ->count();

        $totalFiltered = $totalData;
        $totalRec = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value')))
        {
            
            $processos = DB::table('processo AS p')
                    ->leftJoin('cliente AS cl', 'cl.id', '=', 'p.cliente_id')
                    ->leftJoin('tipoprocesso AS ct', 'ct.id', '=', 'p.tipoprocesso_id')
                    ->leftJoin('estadoprocesso AS s', 's.id', '=', 'p.estado')
                    ->leftJoin('tribunal AS tb', 'tb.id', '=', 'p.tribunal_id')
                    ->leftJoin('juiz AS j', 'j.id', '=', 'p.juiz_id')
                    ->leftJoin('areaprocessual AS ap', 'p.areaprocessual_id', '=', 'ap.id')
                    ->leftJoin('seccao AS se', 'p.seccao_id', '=', 'se.id')
                    ->select('p.id AS case_id', 'p.no_interno', 'se.nome AS seccao', 'ap.designacao AS areaprocessual', 'p.no_processo', 'p.client_position', 'p.party_name', 'p.party_lawyer', 'ct.designacao AS caseType', 's.estado', 'tb.nome AS tribunal', 'j.nome AS juiz', 'cl.nome', 'cl.sobrenome', 'cl.instituicao', 'p.updated_by', 'cl.id AS advo_client_id'
                    )
                    ->where($cond)
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
        } else {
            /*
              |--------------------------------------------
              | For table search filterfrom frontend site.
              |--------------------------------------------
             */
            $search = $request->input('search.value');

            $processos = DB::table('processo AS p')
                    ->leftJoin('cliente AS cl', 'cl.id', '=', 'p.cliente_id')
                    ->leftJoin('tipoprocesso AS ct', 'ct.id', '=', 'p.tipoprocesso_id')
                    ->leftJoin('estadoprocesso AS s', 's.id', '=', 'p.estado')
                    ->leftJoin('tribunal AS tb', 'tb.id', '=', 'p.tribunal_id')
                    ->leftJoin('juiz AS j', 'j.id', '=', 'p.juiz_id')
                    ->leftJoin('areaprocessual AS ap', 'p.areaprocessual_id', '=', 'ap.id')
                    ->leftJoin('seccao AS se', 'p.seccao_id', '=', 'se.id')
                    ->select('p.id AS case_id', 'p.no_interno', 'se.nome AS seccao', 'ap.designacao AS areaprocessual', 'p.no_processo', 'p.client_position', 'p.party_name', 'p.party_lawyer', 'ct.designacao AS caseType', 's.estado', 'tb.nome AS tribunal', 'j.nome AS juiz', 'cl.nome', 'cl.sobrenome', 'cl.instituicao', 'p.updated_by', 'cl.id AS advo_client_id'
                    )
                    ->where($cond)
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

            $totalFiltered = DB::table('processo AS p')
                    ->leftJoin('cliente AS cl', 'cl.id', '=', 'p.cliente_id')
                    ->leftJoin('tipoprocesso AS ct', 'ct.id', '=', 'p.tipoprocesso_id')
                    ->leftJoin('estadoprocesso AS s', 's.id', '=', 'p.estado')
                    ->leftJoin('tribunal AS tb', 'tb.id', '=', 'p.tribunal_id')
                    ->leftJoin('juiz AS j', 'j.id', '=', 'p.juiz_id')
                    ->leftJoin('areaprocessual AS ap', 'p.areaprocessual_id', '=', 'ap.id')
                    ->select('p.id AS case_id', 'ap.designacao AS areaprocessual', 'p.no_processo', 'p.id', 'p.party_name', 'p.party_lawyer', 'ct.designacao AS caseType', 's.estado', 'tb.nome AS tribunal', 'j.nome AS juiz', 'cl.nome', 'cl.sobrenome', 'cl.instituicao', 'p.updated_by', 'cl.id AS advo_client_id'
                    )
                    ->where($cond)
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->count();
        }
        /*
          |----------------------------------------------------------------------------------------------------------------------------------
          | Creating json array with all records based on input from front end site like all,searcheded,pagination record (i.e 10,20,50,100).
          |----------------------------------------------------------------------------------------------------------------------------------
         */
        $data = array();

        if (!empty($processos))
        {
            foreach ($processos as $key => $processo)
            {
                /**
                 * For HTMl action option like edit and delete
                 */
                $show = route('processo.show', encrypt($processo->case_id));

                /**
                 * -/End
                 */
                $first = $processo->party_name;

                $totalComentarios = $this->getTotalComentario($processo->case_id);

                //$class = ($processo->prioridade == 'High') ? 'fa fa-star' : (($processo->prioridade == 'Média') ? 'fa fa-star-half-o' : 'fa fa-star-o');

                if (empty($request->input('search.value')))
                {
                    $final = $totalRec - $start;
                    $row['id'] = $final;
                    $totalRec--;
                } else {
                    $start++;
                    $row['id'] = $start;
                }

                if ($isEdit)
                {
                    $row['name'] = '<div style="font-size:15px;" class="clinthead text-primary">
                       <a  class="text-primary" href="javascript:void(0);" onclick="change_case_important(' . $processo->case_id . ')"></a>'
                            . '&nbsp;<a  class="text-primary" href="' . $show . '">' . htmlspecialchars($processo->no_processo) . '</a></div>
                                        <p class="clinttittle">Natureza: <b>' . htmlspecialchars($processo->areaprocessual) . '</b></p>
                                        <p class="clinttittle">Tipo de processo: <b>' . htmlspecialchars($processo->caseType) . '</b></p>
                                        <p class="clinttittle">N&ordm; interno: <b>' . str_pad($processo->no_interno, 7, '0', STR_PAD_LEFT) . 'BSA' . '</b></p>';
                } else {
                    $row['name'] = '<div style="font-size:15px;"  class="clinthead text-primary"><a class="text-primary" href="javascript:void(0);" ></a>'
                            . '&nbsp;<a  class="text-primary" href="' . $show . '">' . htmlspecialchars($processo->no_processo) . '</a></div>
                                        <p class="clinttittle">Natureza: <b>' . htmlspecialchars($processo->areaprocessual) . '</b></p>
                                        <p class="clinttittle">Tipo de processo: <b>' . htmlspecialchars($processo->caseType) . '</b></p>
                                        <p class="clinttittle">N&ordm; interno: <b>' . str_pad($processo->no_interno, 7, '0', STR_PAD_LEFT) . '</b></p>';
                }

                $row['court'] = '<p class="currenttittle">Tribunal :<b> ' . htmlspecialchars($processo->tribunal) . '</b></p>
                                        <p class="currenttittle">Secção :<b> ' . htmlspecialchars($processo->seccao) . '</b></p>
                                        <p class="currenttittle">Magistrado(a) :<b> ' . htmlspecialchars($processo->juiz) . '</b></p>';

                $row['case'] = '<p class="currenttittle">' . htmlspecialchars($first) . ' <br/><p>';

                $row['status'] = htmlspecialchars($processo->estado);

                if ($isEdit == "1" || $isDelete == "1")
                {
                    $row['options'] = $this->action([
                        'view' => route('processo.show', encrypt($processo->case_id)),
                        'edit' => route('processo.edit', encrypt($processo->case_id)),
                        'documento' => route('processo.docs', encrypt($processo->case_id)),
                        'comentario' => route('processo.comentarios', encrypt($processo->case_id)),
                        'total_comentario' => $totalComentarios,
                        'delete_permission' => $isDelete,
                        'edit_permission' => $isEdit,
                    ]);
                } else {
                    $row['options'] = $this->action([
                        'view' => route('processo.show', $processo->case_id),
                        'documento' => route('processo.docs', encrypt($processo->case_id)),
                        'comentario' => route('processo.comentarios', encrypt($processo->case_id)),
                        'total_comentario' => $totalComentarios,
                    ]);
                }

                $data[] = $row;
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

    public function getLoginUserNameById($id)
    {
        if ($id != '')
        {
            $name = DB::table('admin')->where('id', $id)->first();
            if (!empty($name)) {
                $fullname = $name->first_name . ' ' . $name->last_name;
                return $fullname;
            } else {
                return 'N/A';
            }
        } else {
            return 'N/A';
        }
    }

    public function allCaseList(Request $request)
    {
        
        $user = auth()->user();
        $isEdit = $user->can('case_edit');
        $isDelete = $user->can('case_delete');

        $checkTask = LogActivity::CheckuserType();

        // Listing column to show
        $columns = array(
            0 => 'case_id',
            1 => 'first_name',
            2 => 'last_login_at',
            3 => 'is_active'
        );

        $totalData = DB::table('processo AS p')
                ->leftJoin('cliente AS cl', 'cl.id', '=', 'p.cliente_id')
                ->leftJoin('tipoprocesso AS tp', 'tp.id', '=', 'p.tipoprocesso_id')
                ->leftJoin('estadoprocesso AS s', 's.id', '=', 'p.estado')
                ->leftJoin('tribunal AS tb', 'tb.id', '=', 'p.tribunal_id')
                ->leftJoin('juiz AS j', 'j.id', '=', 'p.juiz_id')
                ->leftJoin('seccao', 'seccao.id', '=', 'p.seccao_id')
                ->leftJoin('intervdesignacao AS idcl', 'idcl.id', '=', 'p.client_position')
                ->leftJoin('areaprocessual AS ap', 'ap.id', '=', 'p.areaprocessual_id')
                ->select('p.id AS case_id', 'p.no_processo', 'ap.designacao AS areaprocessual', 'p.valor_causa', 'seccao.nome AS seccao', 'idcl.designacao AS client_position', 'p.party_name', 'p.party_lawyer', 'j.nome AS juiz', 'tp.designacao AS caseType', 's.estado', 'tb.nome AS tribunal', 'cl.nome', 'cl.sobrenome', 'cl.instituicao', 'cl.tipo AS tipo_cliente', 'p.updated_by', 'cl.id AS advo_client_id', 'p.activo'
                )
                ->when($request->input('areaprocessual'), function ($query, $areaprocessual) {

                    return $query->where('p.areaprocessual_id', $areaprocessual);
                })
//                ->when($checkTask['type'] == "ADV", function ($query) use ($checkTask) {
//                    $query->leftJoin('processo_membro AS pm', 'pm.processo_id', '=', 'p.id');
//                    $query->where('pm.membro', $checkTask['id']);
//                    return $query;
//                })
                ->count();

        $totalRec = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $customcollections = DB::table('processo AS p')
                ->leftJoin('cliente AS cl', 'cl.id', '=', 'p.cliente_id')
                ->leftJoin('tipoprocesso AS tp', 'tp.id', '=', 'p.tipoprocesso_id')
                ->leftJoin('estadoprocesso AS s', 's.id', '=', 'p.estado')
                ->leftJoin('tribunal AS t', 't.id', '=', 'p.tribunal_id')
                ->leftJoin('juiz AS j', 'j.id', '=', 'p.juiz_id')
                ->leftJoin('seccao', 'seccao.id', '=', 'p.seccao_id')
                ->leftJoin('intervdesignacao AS idcl', 'idcl.id', '=', 'p.client_position')
                ->leftJoin('areaprocessual AS ap', 'ap.id', '=', 'p.areaprocessual_id')
                ->select('p.id AS case_id', 'p.no_interno', 'p.no_processo', 'p.valor_causa', 'p.areaprocessual_id', 'ap.designacao AS areaprocessual', 'seccao.nome AS seccao', 'idcl.designacao AS client_position', 'p.party_name', 'p.party_lawyer', 'j.nome AS juiz', 'tp.designacao AS caseType', 's.estado', 't.nome AS tribunal', 'cl.nome', 'cl.sobrenome', 'cl.instituicao', 'cl.tipo AS tipo_cliente', 'p.updated_by', 'cl.id AS advo_client_id', 'p.activo'
                )
                ->when($request->input('areaprocessual'), function ($query, $areaprocessual) {

                    return $query->where('p.areaprocessual_id', $areaprocessual);
                })
//                ->when($checkTask['type'] == "ADV", function ($query) use ($checkTask) {
//                    $query->leftJoin('processo_membro AS pm', 'pm.processo_id', '=', 'p.id');
//                    $query->where('pm.membro', $checkTask['id']);
//                    return $query;
//                })
                ->when($search, function ($query, $search) {
            return $query->where('cl.nome', 'LIKE', "%{$search}%")
                    ->orWhere('cl.sobrenome', 'LIKE', "%{$search}%")
                    ->orWhere('cl.instituicao', 'LIKE', "%{$search}%")
                    ->orWhere('p.no_processo', 'LIKE', "%{$search}%")
                    ->orWhere('p.no_interno', 'LIKE', "%{$search}%")
                    ->orWhere('tp.designacao', 'LIKE', "%{$search}%")
                    ->orWhere('t.nome', 'LIKE', "%{$search}%")
                    ->orWhere('j.nome', 'LIKE', "%{$search}%")
                    ->orWhere('seccao.nome', 'LIKE', "%{$search}%")
                    ->orWhere('idcl.designacao', 'LIKE', "%{$search}%")
                    ->orWhere('ap.designacao', 'LIKE', "%{$search}%");
        });

        $totalFiltered = $customcollections->count();

        $customcollections = $customcollections->offset($start)->limit($limit)->orderBy($order, $dir)->get();

        $data = [];

        foreach ($customcollections as $key => $case)
        {

            $case_list = url('admin/case-list/' . encrypt($case->advo_client_id));

            $clientName = ($case->tipo_cliente == 2) ? $case->nome . ' ' . $case->sobrenome : $case->instituicao;

            if ($case->client_position == 'Petitioner')
            {
                $first = $clientName;
                $second = $case->party_name;
            } else {
                $first = $case->party_name;
                $second = $clientName;
            }

            $totalComentarios = $this->getTotalComentario($case->case_id);

            $tipoProcesso = ($case->areaprocessual_id != 4) ? 'Forma de processo' : 'Tipo de ac&ccedil;&atilde;o';

            //$class = ($case->prioridade == 'Alta') ? 'fa fa-star' : (($case->prioridade == 'Média') ? 'fa fa-star-half-o' : 'fa fa-star-o');

            if ($isEdit == "1") {

                if ($case->activo == 'N') {
                    $priorityModal = '<a class="title text-primary" href="javascript:void(0);"></a>';
                } else {
                    $priorityModal = '<a  class="title text-primary" href="javascript:void(0);" onclick="change_case_important(' . $case->case_id . ')"></a>';
                }
            } else {

                if ($case->activo == 'N') {
                    $priorityModal = '<a class="title text-primary" href="javascript:void(0);"></a>';
                } else {
                    $priorityModal = '<a class="title text-primary" href="javascript:void(0);"></a>';
                }
            }

            if (empty($request->input('search.value'))) {
                $final = $totalRec - $start;
                $row['id'] = $final;
                $totalRec--;
            } else {
                $start++;
                $row['id'] = $start;
            }

            $members = $this->getMembers($case->case_id);
            // dd(  $members);

            if ($case->valor_causa) {

                $row['name'] = '<div style="font-size:15px;"  class="clinthead text-primary">' . $priorityModal . ''
                        . '&nbsp;<a class="title text-primary"  href="' . $case_list . '">' . htmlspecialchars(mb_strtoupper($clientName)) . '</a></div>
                                        <p class="clinttittle">Qualidade: <b>' . htmlspecialchars($case->client_position) . '</b></p>
                                        <p class="clinttittle">Processo: <b>' . htmlspecialchars($case->no_processo) . '</b></p>
                                        <p class="clinttittle">Natureza: <b>' . htmlspecialchars($case->areaprocessual) . '</b></p>
                                        <p class="clinttittle">' . $tipoProcesso . ': <b>' . htmlspecialchars($case->caseType) . '</b></p>
                                        <p class="clinttittle">Valor da causa: <b>' . number_format($case->valor_causa, 3, ',', ' ') . ' AKZ' . '</b></p>
                                        <p class="clinttittle">N&ordm; interno: <b>' . str_pad($case->no_interno, 7, '0', STR_PAD_LEFT) . 'HCAC</b></p>
                                        <p class="clinttittle"><b>Advogado(s)</b></p>
                                        ' . $members;
            } else {

                $row['name'] = '<div style="font-size:15px;"  class="clinthead text-primary">' . $priorityModal . ''
                        . '&nbsp;<a class="title text-primary"  href="' . $case_list . '">' . htmlspecialchars(mb_strtoupper($clientName)) . '</a></div>
                                        <p class="clinttittle">Qualidade: <b>' . htmlspecialchars($case->client_position) . '</b></p>
                                        <p class="clinttittle">Processo: <b>' . htmlspecialchars($case->no_processo) . '</b></p>
                                        <p class="clinttittle">Natureza: <b>' . htmlspecialchars($case->areaprocessual) . '</b></p>
                                        <p class="clinttittle">' . $tipoProcesso . ': <b>' . htmlspecialchars($case->caseType) . '</b></p>
                                        <p class="clinttittle">N&ordm; interno: <b>' . str_pad($case->no_interno, 7, '0', STR_PAD_LEFT) . 'HCAC</b></p>
                                        <p class="clinttittle"><b>Advogado(s)</b></p>
                                        ' . $members;
            }

            if ($case->tribunal) {

                $row['court'] = '<p class="currenttittle">Tribunal :<b> ' . htmlspecialchars($case->tribunal) . '</b></p>
                                        <p class="currenttittle">Secção :<b> ' . htmlspecialchars($case->seccao) . '</b></p>
                                        <p class="currenttittle">Magistrado(a) :<b> ' . htmlspecialchars($case->juiz) . '</b></p>';
            } else {

                $row['court'] = '';
            }

            $row['case'] = '<p class="currenttittle">' . htmlspecialchars($case->party_name) . ' <br/></p>'
                    . '<p class="currenttittle"><b>Posição: </b>' . '' . '</p>';

            //$nestedData['next_date'] = '<p class="currenttittle">'.date('d-m-Y',strtotime($case->next_date)).'<p>'.'<a class="btn btn-link" href="'.$nextDate.'">Add Next Date</a>';
            $row['status'] = htmlspecialchars($case->estado);


            if ($isEdit == "1" || $isDelete == "1") {
                $row['options'] = $this->action([
                    'view' => route('processo.show', encrypt($case->case_id)),
                    'edit' => route('processo.edit', encrypt($case->case_id)),
                    'documento' => route('processo.docs', encrypt($case->case_id)),
                    'comentario' => route('processo.comentarios', encrypt($case->case_id)),
                    'total_comentario' => $totalComentarios,
                    'delete_permission' => $isDelete,
                    'edit_permission' => $isEdit,
                ]);
            } else {
                $row['options'] = $this->action([
                    'view' => route('processo.show', $case->case_id),
                    'documento' => route('processo.docs', encrypt($case->case_id)),
                    'comentario' => route('processo.comentarios', encrypt($case->case_id)),
                    'total_comentario' => $totalComentarios,
                ]);
            }

            $data[] = $row;
        }

        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data,
        );

        return response()->json($json_data);
    }

    public function getMembers($id)
    {
        $getTaskMemberArr = ProcessoMembro::where('processo_id', $id)->pluck('membro');

        $getmulti = Admin::whereIn('id', $getTaskMemberArr)->get();
        $con = "<div style='display: flex;''>";

        foreach ($getmulti as $key => $value)
        {
            $con .= '<div title="' . $value->pessoasingular->nome . ' ' . $value->pessoasingular->sobrenome . '" data-letters="' . ucfirst(substr($value->pessoasingular->nome, 0, 1)) . '"> </div>';
        }
        $con .= "</div>";

        return $con;
    }

    public function generateNInternoProcesso()
    {
        // $advocate_id = $this->getLoginUserId();
        $configuraGeral = ConfiguracaoGeral::where('id', "1")->first();

        $no_interno = $configuraGeral->no_interno_processo + 1;

        $no_interno = $my_val = str_pad($no_interno, 7, '0', STR_PAD_LEFT);

        return $no_interno;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        if (Gate::denies('case_add'))
            return back();

        
            //Get case status
            $data['caseStatuses'] = EstadoProcesso::where('activo', 'S')
                    ->orderBy('estado', 'asc')
                    ->get();

            $data['client_list'] = Cliente::where('activo', 'S')
                    ->get();

            $data['areasprocessuais'] = AreaProcessual::all();

            $data['users'] = $this->admin->listarAdvogados();

            $data['intervsdesignacao'] = IntervenienteDesignacao::all();

            return view('admin.processo.add_case', $data);
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreProcessoRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProcessoRequest $request)
    {

        $index = 0;
        $user = auth()->user();

        
        //Inicia o Database Transaction
        DB::beginTransaction();
        
        $processo = new Processo();

        $processo->cliente_id = $request->client_name;
        $processo->client_position = $request->qualidade;
        $processo->party_name = $request->input('parties_detail.' . $index . '.party_name');
        $processo->party_lawyer = $request->input('parties_detail.' . $index . '.party_advocate');
        $processo->no_processo = addslashes($request->no_processo);
        $processo->areaprocessual_id = $request->areaprocessual;
        $processo->tipoprocesso_id = $request->tipo_processo;
        $processo->estado = $request->estado;
        $processo->valor_causa = $request->valor_causa;
        $processo->orgao = $request->orgao;
        $processo->orgaojudiciario_id = $request->orgaojudiciario;
        $processo->orgao_extrajudicial = addslashes($request->orgaoextrajudicial);
        $processo->tipo_crime = $request->tipo_crime;
        $processo->tribunal_id = $request->tribunal;
        $processo->seccao_id = $request->seccao;
        $processo->instrutor = addslashes($request->instrutor);
        $processo->procurador = addslashes($request->procurador);
        $processo->juiz_id = $request->juiz;
        $processo->escrivao = trim(addslashes($request->escrivao));
        $processo->mandatario_judicial = trim(addslashes($request->mandatario_judicial));
        $processo->data_registo = date('Y-m-d');
        $processo->descricao = addslashes($request->descricao);
        $processo->no_interno = $this->generateNInternoProcesso();

        $processo->updated_by = auth()->id();
        $processo->save();

        $configuracaoGeral = ConfiguracaoGeral::where('id', "1")->first();
        $configuracaoGeral->no_interno_processo = $configuracaoGeral->no_interno_processo + 1;
        $configuracaoGeral->save();
        
        $cont = 0;

        if (isset($request->assigned_to) && count($request->assigned_to))
        {
            foreach ($request->assigned_to as $key => $value)
            {
                # Arrary in assigne employee...
                $processoMembro = new ProcessoMembro();
                $processoMembro->processo_id = $processo->id;
                $processoMembro->membro = $value;
                $processoMembro->save();
                
                $cont += 1;
            }
        }
        
        if($cont){
            //Sucesso!
            DB::commit();
        }else{
           //Fail, desfaz as alterações na base de dados
            DB::rollBack();
        }


        if ($processo->id)
        {
            //Add records to parties table for multiple records or single.
            if (!empty($request->parties_detail))
            {
                foreach ($request->parties_detail as $key => $val)
                {
                    if ($request->input('parties_detail.' . $key . '.party_name') != null && $request->input('parties_detail.' . $key . '.qualidade_pc') != null)
                    {
                        $party = new ParteContraria();
                        $party->processo_id = $processo->id;
                        $party->qualidade = $request->input('parties_detail.' . $key . '.qualidade_pc');
                        $party->nome = $request->input('parties_detail.' . $key . '.party_name');
                        $party->party_advocate = $request->input('parties_detail.' . $key . '.party_advocate');
                        $party->save();
                    }
                }
            }
            $historicoProcesso = new HistoricoProcesso();

            $historicoProcesso->advogado_id = $user->admin->id;
            $historicoProcesso->processo_id = $processo->id;
            $historicoProcesso->bussiness_on_date = date('Y-m-d H:i:s', strtotime(LogActivity::commonDateFromat($request->next_date)));
            $historicoProcesso->updated_by = auth()->id();
            $historicoProcesso->save();
        }

        $membros = $processo->membros;

        $this->processo->notificacao($membros, $processo);

        return redirect()->route('processo.index')->with('success', "Processo registado com o nº interno " . $processo->no_interno . 'BSA');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    protected function validator_case(array $data)
    {
        return Validator::make($data, [
                    'areaprocessual' => 'required',
                    'tipo_processo' => 'required',
                    'orgao' => 'required',
                    'estado' => 'required',
                    'client_name' => 'required'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $user = auth()->user();

        if (!$user->can('case_list') && $user->user_type != 'Cliente')
            return back();

        $processo = DB::table('processo AS p')
                ->leftJoin('cliente AS cl', 'cl.id', '=', 'p.cliente_id')
                ->leftJoin('tipoprocesso AS ct', 'ct.id', '=', 'p.tipoprocesso_id')
                ->leftJoin('estadoprocesso AS s', 's.id', '=', 'p.estado')
                ->leftJoin('tribunal AS tb', 'tb.id', '=', 'p.tribunal_id')
                ->leftJoin('juiz AS j', 'j.id', '=', 'p.juiz_id')
                ->leftJoin('areaprocessual AS ap', 'p.areaprocessual_id', '=', 'ap.id')
                ->select('p.id AS processo_id', 'ap.designacao AS areaprocessual', 'p.cliente_id', 'p.client_position', 'p.party_name', 'p.party_lawyer', 'p.no_processo', 'ct.designacao AS caseType', 's.estado', 'tb.nome AS tribunal', 'j.nome AS juiz', DB::raw('CONCAT(cl.nome, " ",cl.sobrenome) AS full_name'), 'p.descricao'
                )
                ->where('p.id', decrypt($id))
                ->first();

        $data = $this->getRespon($processo->cliente_id, $processo->processo_id, $processo->client_position);

        $data['case'] = $processo;

        return view('admin.processo.view.view_case_details', $data);
    }

    public function getRespon($client_id, $court_case_id, $client_position)
    {
        //for petitioner and respondent
        $client_single_name = Cliente::findOrFail($client_id);
        $client_single = $client_single_name->full_name;
        $admin = Admin::with('pessoasingular')->find(1);

        $clientPar = array();
        $clientPartiesInvoive = ClienteParte::where('cliente_id', $client_id)->get();
        if (count($clientPartiesInvoive) && !empty($clientPartiesInvoive)) {
            foreach ($clientPartiesInvoive as $key => $value) {
                $clientPar[$key]['party_name'] = $value['party_firstname'] . ' ' . $value['party_middlename'] . ' ' . $value['party_lastname'];
                if (empty($value['party_advocate'])) {
                    $clientPar[$key]['party_advocate'] = $admin->pessoasingular->nome . ' ' . $admin->pessoasingular->sobrenome;
                } else {
                    $clientPar[$key]['party_advocate'] = $value['party_advocate'];
                }
            }
        }

        $mearge = array();
        $cli[0]['party_name'] = $client_single;
        $cli[0]['party_advocate'] = ' ';

        if (!empty($clientPar) && count($clientPar) > 0)
        {
            $mearge = array_merge($cli, $clientPar);
        } else {
            $mearge = $cli;
        }
        $second = [];

        $respondent = ParteContraria::select('nome', 'qualidade', "party_advocate")->where('processo_id', $court_case_id)->get();
        if (count($respondent) && !empty($respondent))
        {
            $second = collect($respondent)->toArray();
        }
        // $result=collect($case)->toArray();

        $PetitiAndRespo = array();
        if ($client_position == "Petitioner")
        {
            $result['petitioner_and_advocate'] = $mearge;
            $result['respondent_and_advocate'] = $second;
        } else {
            $result['petitioner_and_advocate'] = $second;
            $result['respondent_and_advocate'] = $mearge;
        }
        return $result;
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
        if (!$user->can('case_edit'))
            return back();

        $checkTask = LogActivity::CheckuserType();

//        if ($checkTask['type'] == "ADV") {
//            $check = ProcessoMembro::where('processo_id', decrypt($id))->where('membro', $checkTask['id'])->count();
//            if ($check == 0)
//                return back();
//        }

        $data['areasProcessuais'] = AreaProcessual::all();

        //Get case status all by added superadmin + added by advocate

        $data['processo'] = $this->processo->with(
                        ['tribunal', 'tipoProcesso', 'orgaoJudiciario',
                            'seccao', 'juiz', 'posicaocliente', 'estadoprocesso'
                ])->findOrFail(decrypt($id));

        $data['client_list'] = Cliente::all();

        $data['parties'] = ParteContraria::where('processo_id', decrypt($id))->get();

        $data['users'] = $this->admin->listarAdvogados();

        $data['user_ids'] = array();

        $data['user_ids'] = ProcessoMembro::where('processo_id', decrypt($id))
                ->pluck('membro')
                ->toArray();

        $data['intervsdesignacao'] = IntervenienteDesignacao::all();

        return view('admin.processo.edit_case', $data);
    }

    /**
     * 
     * @param App\Http\Requests\UpdateProcessoRequest
     * @param type $id
     * @return type
     */
    public function update(UpdateProcessoRequest $request, $id)
    {
        
        $processo = $this->processo->findOrFail(decrypt($id));

        $index = '0';

        $processo->no_processo = addslashes($request->no_processo);
        $processo->areaprocessual_id = $request->areaprocessual;
        $processo->tipoprocesso_id = $request->tipo_processo;
        $processo->orgao = $request->orgao;
        $processo->orgao_extrajudicial = addslashes($request->orgaoextrajudicial);
        $processo->orgaojudiciario_id = $request->orgaojudiciario;
        $processo->tipo_crime = $request->tipo_crime;
        $processo->tribunal_id = $request->tribunal;
        $processo->seccao_id = $request->seccao;
        $processo->juiz_id = $request->juiz;
        $processo->escrivao = trim(addslashes($request->escrivao));
        $processo->mandatario_judicial = trim(addslashes($request->mandatario_judicial));
        $processo->estado = $request->estado;
        $processo->valor_causa = $request->valor_causa;
        $processo->instrutor = addslashes($request->instrutor);
        $processo->procurador = addslashes($request->procurador);
        $processo->cliente_id = $request->client_name;
        $processo->client_position = $request->qualidade;
        $processo->party_name = $request->input('parties_detail.' . $index . '.party_name');
        $processo->party_lawyer = $request->input('parties_detail.' . $index . '.party_advocate');
        $processo->descricao = addslashes($request->descricao);

        $processo->save();

        //assign user
        $getCaseMember = ProcessoMembro::where('processo_id', decrypt($id))->delete();

        if (isset($request->assigned_to) && count($request->assigned_to))
        {
            foreach ($request->assigned_to as $key => $value)
            {
                # Arrary in assigne employee...
                $processoMembro = new ProcessoMembro();
                $processoMembro->processo_id = $processo->id;
                $processoMembro->membro = $value;
                $processoMembro->save();
            }
        }

        if ($processo->id)
        {
            //Update records to parties table for multiple records or single.
            if (!empty($request->parties_detail))
            {
                $delete = ParteContraria::where('processo_id', decrypt($id))->delete();
                foreach ($request->parties_detail as $key => $val)
                {
                    if ($request->input('parties_detail.' . $key . '.party_name') != null && $request->input('parties_detail.' . $key . '.qualidade_pc') != null)
                    {
                        $party = new ParteContraria();
                        $party->processo_id = $processo->id;
                        $party->qualidade = $request->input('parties_detail.' . $key . '.qualidade_pc');
                        $party->nome = $request->input('parties_detail.' . $key . '.party_name');
                        $party->party_advocate = $request->input('parties_detail.' . $key . '.party_advocate');
                        $party->save();
                    }
                }
            }
        }

        return redirect()->route('processo.index')->with('success', "Processo actualizado.");
    }

    public function getNextDateModal($case_id)
    {
        $caseStatuses = EstadoProcesso::where('activo', 'S')
                ->orderBy('estado', 'asc')
                ->get();

        $judges = Juiz::where('activo', 'S')
                ->orderBy('nome', 'asc')
                ->get();

        $case = $this->processo->findorfail($case_id);
        return view('admin.case.modal_next_date', ['caseStatuses' => $caseStatuses, 'case' => $case, 'judges' => $judges]);
    }

    public function getChangeCourtModal($case_id)
    {

        $caseStatuses = EstadoProcesso::where('activo', 'S')->orderBy('estado', 'asc')->get();

        $judges = Juiz::where('activo', 'S')
                ->orderBy('nome', 'asc')
                ->get();

        $case = $this->processo->findOrFail($case_id);

        return view('admin.processo.modal_change_court', ['caseStatuses' => $caseStatuses, 'case' => $case, 'judges' => $judges]);
    }

    //Function for change case priority

    public function addNextDate($case_id)
    {
        $caseStatuses = EstadoProcesso::where('activo', 'S')
                ->orderBy('estado', 'desc')
                ->get();

        $judges = Juiz::where('activo', 'S')->get();

        $case = $this->processo->findOrFail($case_id);

        return view('admin.processo.modal_next_date', ['caseStatuses' => $caseStatuses, 'case' => $case, 'judges' => $judges]);
    }

    public function getCaseImportantModal($case_id)
    {
        $case = $this->processo->findOrFail($case_id);

        return view('admin.processo.modal_change_priority', ['case' => $case]);
    }

    public function changeCasePriority(Request $request)
    {
        $processo = $this->processo->findOrFail($request->id);

        //activity logs
        $redirect_url = url('admin/processo');
        $activity = 'Case priority ' . ' to ' . $request->priority . ' Registration no :&nbsp;' . $processo->no_processo;

        $processo->prioridade = $request->priority;
        $processo->save();

        echo 'success';
    }

    public function restoreCase($case_id, Request $request)
    {
        $processo = $this->processo->findOrFail($case_id);

        $processo->activo = 'S';

        $processo->update();

        $redirect_url = url('admin/processo/' . $case_id);
        $activity = '#case no&nbsp;' . $processo->no_processo;
        LogActivity::addToLog('Restore case', $activity, $redirect_url);
        return redirect()->to('admin/case-archived')->with('success', "Case no# " . $processo->no_processo . "  restore successfully.");
    }

    public function caseNextDate(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
                    //'judgeType'     => 'required',
                    'case_status' => 'required',
                    'next_date' => 'sometimes',
        ]);

        if ($validatedData->passes())
        {

            //Update caourt case table with latest status and next date
            $processo = $this->processo->findorfail($request->case_id);

            //Update next date with bussiness on date

            $caseLog = HistoricoProcesso::where('processo_id', $request->case_id)->where('bussiness_on_date', $CourtCase->next_date)->first();

            if ($request->next_date == '')
            {
                $caseLogUpdate = HistoricoProcesso::findorfail($caseLog->id);

                $caseLogUpdate->judge_type = $processo->judge_type;

                $caseLogUpdate->court_no = $processo->court_no;
                $caseLogUpdate->judge_name = $processo->judge_name;
                $caseLogUpdate->updated_by = auth()->id();
                $caseLogUpdate->remark = $request->remarks;

                if ($request->decision_date != '')
                {
                    $processo->decision_date = date('Y-m-d H:i:s', strtotime(LogActivity::commonDateFromat($request->decision_date)));

                    $processo->nature_disposal = $request->nature_disposal;
                    $processo->is_active = 'No';

                    //Insert next date on bussines on date
                    $caseLogUpdate->hearing_date = date('Y-m-d H:i:s', strtotime(LogActivity::commonDateFromat($request->decision_date)));

                    $caseLogInsert = new CaseLog();

                    $caseLogInsert->advocate_id = "1";
                    $caseLogInsert->court_case_id = $request->case_id;
                    $caseLogInsert->judge_type = $processo->judge_type;
                    $caseLogInsert->case_status = $request->case_status;
                    $caseLogInsert->court_no = $processo->court_no;
                    $caseLogInsert->judge_name = $processo->judge_name;
                    $caseLogInsert->bussiness_on_date = date('Y-m-d H:i:s', strtotime(LogActivity::commonDateFromat($request->decision_date)));
                    $caseLogInsert->updated_by = auth()->id();

                    $caseLogInsert->save();

                    $processo->next_date = date('Y-m-d H:i:s', strtotime(LogActivity::commonDateFromat($request->decision_date)));

                    if ($processo->is_nb == 'Yes')
                    {
                        $activity_msg = 'Case status change from No Board to Archived. ';
                    } else {
                        $activity_msg = 'Case status change from Running to Archived. ';
                    }
                } else {
                    $caseLogUpdate->case_status = $request->case_status;
                }
                $caseLogUpdate->save();

                $processo->case_status = $request->case_status;

                $processo->updated_by = "1";

                if ($request->is_nb == 'Yes')
                {
                    $processo->is_nb = 'Yes';
                    $processo->activo = 'S';
                }

                $processo->update();
            } else {

                $caseLogUpdate = HistoricoProcesso::findOrFail($caseLog->id);

                $caseLogUpdate->judge_type = $processo->judge_type;

                $caseLogUpdate->court_no = $processo->court_no;
                $caseLogUpdate->judge_name = $processo->judge_name;
                $caseLogUpdate->hearing_date = date('Y-m-d H:i:s', strtotime(LogActivity::commonDateFromat($request->next_date)));
                $caseLogUpdate->updated_by = auth()->id();

                $caseLogUpdate->remark = $request->remarks;
                $caseLogUpdate->save();

                //Insert next date on bussines on date

                $caseLogInsert = new HistoricoProcesso();

                $caseLogInsert->advocate_id = "1";
                $caseLogInsert->court_case_id = $request->case_id;
                $caseLogInsert->judge_type = $processo->judge_type;
                $caseLogInsert->case_status = $request->case_status;
                $caseLogInsert->court_no = $processo->court_no;
                $caseLogInsert->judge_name = $processo->judge_name;
                $caseLogInsert->bussiness_on_date = date('Y-m-d H:i:s', strtotime(LogActivity::commonDateFromat($request->next_date)));
                $caseLogInsert->updated_by = auth()->id();
                $caseLogInsert->save();

                if ($processo->decision_date != '' && $processo->is_active == 'No' && ($request->case_status != 'Disposed' || $request->case_status != 'Closed')) {

                    $processo->decision_date = NULL;

                    $processo->nature_disposal = NULL;
                    $processo->activo = 'S';
                } else {
                    if ($processo->is_nb == 'No') {
                        $activity_msg = 'Add next date of ';
                    } else {
                        $activity_msg = 'Case status change from No Board to Running.';
                    }
                }
                $processo->case_status = $request->case_status;
                $processo->updated_by = "1";
                $processo->next_date = date('Y-m-d H:i:s', strtotime(LogActivity::commonDateFromat($request->next_date)));
                $processo->is_nb = 'No';
                $processo->update();
            }
            echo 'success';
        }
    }

    public function caseHistory($case_id)
    {
        return view('admin.processo.view.view_case_history', ['case_id' => $case_id]);
    }

    public function allCaseHistoryList(Request $request)
    {
        // Listing column to show
        $columns = array(
            0 => 'judge',
            1 => 'business_on_date',
            2 => 'hearing_date',
            3 => 'purpose_of_hearing'
        );

        $case_id = $request->case_id;

        $totalData = DB::table('historico_processo AS cl')
                ->join('processo AS p', 'cl.processo_id', '=', 'p.id')
                ->join('juiz AS j', 'j.id', '=', 'p.juiz_id')
                ->join('estadoprocesso AS ep', 'ep.id', '=', 'p.estado')
                ->select('cl.id AS case_log_id', 'cl.bussiness_on_date', 'cl.hearing_date', 'j.nome'
                        , 'ep.estado')
                ->where('cl.processo_id', $case_id)
                ->count();

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $customcollections = DB::table('historico_processo AS cl')
                ->join('processo AS p', 'cl.processo_id', '=', 'p.id')
                ->join('juiz AS j', 'j.id', '=', 'p.juiz_id')
                ->join('estadoprocesso AS ep', 'ep.id', '=', 'p.estado')
                ->select('cl.id AS case_log_id', 'cl.bussiness_on_date', 'cl.hearing_date', 'j.nome', 'ep.estado')
                ->where('cl.processo_id', $case_id)
                ->when($search, function ($query, $search) {
            $query->where('j.nome', 'LIKE', "%{$search}%")
            ->orWhere('ep.estado', 'LIKE', "%{$search}%");
        });

        $totalFiltered = $customcollections->count();

        $customcollections = $customcollections->offset($start)->limit($limit)->orderBy($order, $dir)->get();

        $data = [];

        foreach ($customcollections as $key => $log)
        {
            $row['judge'] = $log->nome;
            $row['business_on_date'] = date(LogActivity::commonDateFromatType(), strtotime(LogActivity::commonDateFromat($log->bussiness_on_date)));
            if ($log->hearing_date != '') {
                $row['hearing_date'] = date(LogActivity::commonDateFromatType(), strtotime(LogActivity::commonDateFromat($log->hearing_date)));
            } else {
                $row['hearing_date'] = '';
            }
            $bod = "'" . date('d-m-Y', strtotime($log->bussiness_on_date)) . "'";
            $remarks = "'" . 'N/A' . "'";

            $row['purpose_of_hearing'] = $log->estado;
            $row['remarks'] = '<a href="javascript:void(0);" onclick="showRemark(' . $bod . ',' . $remarks . ');"  class="text-center"><i class="fa fa-eye fa-2x"></i></a>';

            $data[] = $row;
        }

        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data,
        );

        return response()->json($json_data);
    }

    public function caseTransfer($case_id)
    {
        return view('admin.processo.view.view_case_transfer', ['case_id' => $case_id]);
    }

    public function allCaseTransferList(Request $request)
    {

        // Listing column to show
        $columns = array(
            0 => 'case_transfer_id',
            1 => 'registration_no',
            2 => 'transferDate',
            3 => 'judge_name',
            4 => 'to_court_no'
        );

        $case_id = $request->case_id;

        $totalData = DB::table('case_transfer AS ct')
                ->join('processo AS p', 'ct.processo_id', '=', 'p.id')
                ->join('juiz AS j', 'ct.from_juiz', '=', 'j.id')
                ->join('juiz AS jt', 'ct.to_juiz', '=', 'jt.id')
                ->select('ct.id AS case_transfer_id', 'ct.transfer_date AS transferDate', 'j.nome'
                        , 'jt.nome AS transferJudge')
                ->where('ct.processo_id', $case_id)
                ->count();

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $customcollections = DB::table('case_transfer AS ct')
                ->join('processo AS p', 'ct.processo_id', '=', 'p.id')
                ->join('juiz AS j', 'ct.from_juiz', '=', 'j.id')
                ->join('juiz AS jt', 'ct.to_juiz', '=', 'jt.id')
                ->select('ct.id AS case_transfer_id', 'ct.transfer_date AS transferDate', 'j.nome'
                        , 'jt.nome AS transferJudge')
                ->where('ct.processo_id', $case_id)
                ->where(function ($query) use ($search) {
            $query->where('ct.transfer_date', 'LIKE', "%{$search}%");
        });

        $totalFiltered = $customcollections->count();

        $customcollections = $customcollections->offset($start)->limit($limit)->orderBy($order, $dir)->get();

        $data = [];

        foreach ($customcollections as $key => $log)
        {
            $row['id'] = $log->case_transfer_id;
            $row['registration_no'] = '';
            $row['transfer_date'] = date(LogActivity::commonDateFromatType(), strtotime(LogActivity::commonDateFromat($log->transferDate)
            ));
            $row['from'] = $log->nome;
            $row['to'] = $log->transferJudge;

            $data[] = $row;
        }

        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data,
        );

        return response()->json($json_data);
    }

    public function destroy($id)
    {
        //
    }

    public function downloadPdf($id, $action)
    {
        $data['setting'] = ConfiguracaoGeral::with('endereco')->where('id', "1")->first();

        $case = DB::table('processo AS p')
                ->leftJoin('cliente AS ac', 'ac.id', '=', 'p.cliente_id')
                ->leftJoin('tipoprocesso AS ct', 'ct.id', '=', 'p.tipoprocesso_id')
                ->leftJoin('estadoprocesso AS e', 'e.id', '=', 'p.estado')
                ->leftJoin('tribunal AS tb', 'tb.id', '=', 'p.tribunal_id')
                ->leftJoin('juiz AS j', 'j.id', '=', 'p.juiz_id')
                ->leftJoin('areaprocessual AS ap', 'p.areaprocessual_id', '=', 'ap.id')
                ->leftJoin('seccao AS s', 'p.seccao_id', '=', 's.id')
                ->select('p.id AS case_id', 'ap.designacao AS areaprocessual', 's.nome AS seccao', 'p.cliente_id AS client_id', 'p.client_position', 'p.party_name', 'p.party_lawyer', 'p.no_processo', 'p.prioridade', 'ct.designacao AS caseType', 'e.estado', 'tb.nome AS tribunal', 'j.nome AS judgeType', DB::raw('CONCAT(ac.nome, " ",ac.sobrenome) AS full_name'), 'p.descricao')
                ->where('p.id', decrypt($id))
                ->first();

        $data['associatedName'] = Admin::select('associated_name')->where('id', '1')->first();
        $data['case'] = $case;

        $getRespo = $this->getRespon($case->client_id, $case->case_id, $case->client_position);
        $data['petitioner_and_advocate'] = $getRespo['petitioner_and_advocate'];
        $data['respondent_and_advocate'] = $getRespo['respondent_and_advocate'];

        //case hestroy
        $getHistory = DB::table('historico_processo AS hp')
                ->join('processo AS p', 'hp.processo_id', '=', 'p.id')
                ->join('juiz AS j', 'j.id', '=', 'p.juiz_id')
                ->join('estadoprocesso AS s', 's.id', '=', 'p.estado')
                ->select('hp.id AS case_log_id', 'hp.bussiness_on_date', 'hp.hearing_date', 'j.nome'
                        , 's.estado')
                ->where('hp.processo_id', $id)
                ->get();

        $data['history'] = array();
        if (count($getHistory) > 0 && !empty($getHistory)) {
            $data['history'] = $getHistory;
        }
        //for transfer
        $transfer = DB::table('case_transfer AS ct')
                ->join('processo AS p', 'ct.processo_id', '=', 'p.id')
                ->join('juiz AS j', 'ct.from_juiz', '=', 'j.id')
                ->join('juiz AS jt', 'ct.to_juiz', '=', 'jt.id')
                ->select('ct.id AS case_transfer_id', 'ct.transfer_date AS transferDate', 'j.nome'
                        , 'jt.nome AS transferJudge')
                ->where('ct.processo_id', $id)
                ->get();

        $data['transfer'] = array();
        if (count($transfer) > 0 && !empty($transfer)) {
            $data['transfer'] = $transfer;
        }

        if ($action == "print")
        {
            //pdf view
            $pdf = PDF::loadView('pdf.welcome', $data);
            return $pdf->stream();
        } else {

            //pdf download
            $pdf = PDF::loadView('pdf.welcome', $data);
            $filename = time() . ".pdf";
            return $pdf->download($filename);
        }
    }

    public function transferCaseCourt(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
                    'judge_type' => 'required',
                    'court_number' => 'required',
                    'transfer_date' => 'required',
        ]);

        if ($validatedData->passes())
        {

            $toCourt = $request->court_number;
            $CourtCase = $this->processo->findorfail($request->case_id);

            $fromCourt = $CourtCase->court_no;
            $case_transfer = new CaseTransfer();

            $case_transfer->advocate_id = "1";
            $case_transfer->court_case_id = $CourtCase->id;
            $case_transfer->from_judge_type = $CourtCase->judge_type;
            $case_transfer->to_judge_type = $request->judge_type;
            $case_transfer->from_court_no = $CourtCase->court_no;
            $case_transfer->to_court_no = $request->court_number;
            $case_transfer->judge_name = $request->judge_name;
            $case_transfer->transfer_date = date('Y-m-d H:i:s', strtotime(LogActivity::commonDateFromat($request->transfer_date)));

            $case_transfer->save();

            $CourtCase->court_no = $request->court_number;
            $CourtCase->judge_type = $request->judge_type;
            $CourtCase->judge_name = $request->judge_name;

            $CourtCase->save();
            echo 'success';
        }
        return back()->with('errors', $validatedData->errors());
    }

    public function getTotalComentario($cod_processo)
    {
        $processo = $this->processo->with('comentarios')->find($cod_processo);

        return $processo->comentarios->count();
    }

}
