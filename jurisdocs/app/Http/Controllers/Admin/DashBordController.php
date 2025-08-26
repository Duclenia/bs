<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Processo;
use App\Models\EstadoProcesso;
use App\Models\Juiz;
use App\Models\Agenda;
use App\Models\ConfiguracaoGeral;
use DB;
use PDF;
use App\Models\Admin;
use App\Helpers\LogActivity;

class DashBordController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getcases($court, $judge_type, $startDate, $endDate)
    {
        // $advocate_id = $this->getLoginUserId();
        $totalData = DB::table('processo AS p')
                        ->leftJoin('cliente AS c', 'c.id', '=', 'p.cliente_id')
                        ->leftJoin('tipoprocesso AS tp', 'tp.id', '=', 'p.tipoprocesso_id')
                        ->leftJoin('estadoprocesso AS ep', 'ep.id', '=', 'p.estado')
                        ->leftJoin('tribunal AS t', 't.id', '=', 'p.tribunal_id')
                        ->leftJoin('juiz AS j', 'j.id', '=', 'p.juiz_id')
                        ->select('p.id AS case_id', 'p.client_position', 'p.party_name', 'p.party_lawyer', 'p.no_processo', 'tp.designacao AS caseType', 'ep.estado', 't.nome', 'j.nome', 'c.nome', 'c.sobrenome', 'c.instituicao'
                        )
                        // ->where('case.advocate_id',$advocate_id)
                        ->where('p.activo', 'S')
                        ->where('p.tribunal_id', $court)
                        //->whereDate('case.next_date', '>=',$startDate)
                        // ->whereDate('case.next_date', '<=', $endDate)
                        ->orderBy('p.id', 'desc')
                        ->get()->groupBy('court')->toArray();
        return array_shift($totalData);
    }

    public function getcasesByIds($court, $judge_type, $date)
    {

        // $advocate_id = $this->getLoginUserId();

        $checkTask = LogActivity::CheckuserType();

        $totalData = DB::table('processo AS p')
                        ->leftJoin('historico_processo AS hp', 'hp.processo_id', '=', 'p.id')
                        ->leftJoin('cliente AS c', 'c.id', '=', 'p.cliente_id')
                        ->leftJoin('tipoprocesso AS tp', 'tp.id', '=', 'p.tipoprocesso_id')
                        ->leftJoin('estadoprocesso AS ep', 'ep.id', '=', 'p.estado')
                        ->select('p.id AS case_id', 'hp.bussiness_on_date', 'hp.hearing_date', 'p.client_position', 'p.party_name', 'p.party_lawyer', 'ep.estado', 'c.nome', 'c.sobrenome', 'c.instituicao'
                        )
                        // ->where('case.advocate_id',$advocate_id)
                        ->where('p.activo', 'S')
                        ->where('hp.bussiness_on_date', $date)
                        ->where('p.juiz_id', $judge_type)
                        ->when($checkTask['type'] == "ADV", function ($query) use($checkTask) {
                            $query->leftJoin('processo_membro AS pm', 'pm.processo_id', '=', 'p.id');
                            $query->where('pm.membro', $checkTask['id']);
                            return $query;
                        })
                        ->distinct()
                        ->get()->toArray();
        return $totalData;
    }

    /**
     *
     * @param Request $request
     * @return type
     */
    public function index(Request $request)
    {

        $checkTask = LogActivity::CheckuserType();
        //filter by date
        $date = date('Y-m-d');

        $data['date'] = $date;

        if (isset($request->client_case) && !empty($request->client_case)) {

            $date = date('Y-m-d', strtotime(LogActivity::commonDateFromat($request->client_case)));
            $data['date'] = $date;
        }

        //get login user id
        // dd(  $advocate_id);
        $casesCount = DB::table('processo AS p')
                        ->leftJoin('historico_processo AS hp', 'hp.processo_id', '=', 'p.id')
                        ->leftJoin('cliente AS c', 'c.id', '=', 'p.cliente_id')
                        ->leftJoin('tipoprocesso AS tp', 'tp.id', '=', 'p.tipoprocesso_id')
                        ->leftJoin('estadoprocesso AS ep', 'ep.id', '=', 'p.estado')
                        ->select('p.id AS case_id', 'hp.bussiness_on_date', 'hp.hearing_date', 'p.client_position', 'p.party_name', 'p.party_lawyer', 'tp.designacao AS caseType', 'ep.estado', 'c.nome', 'c.sobrenome', 'c.instituicao'
                        )
                        // ->where('case.advocate_id',$advocate_id)
                        ->where('p.activo', 'S')
                        ->where('hp.bussiness_on_date', $date)
//                        ->when($checkTask['type'] == "ADV", function ($query) use($checkTask) {
//                            $query->leftJoin('processo_membro AS pm', 'pm.processo_id', '=', 'p.id');
//                            $query->where('pm.membro', $checkTask['id']);
//                            return $query;
//                        })
                        ->distinct()
                        ->get()->toArray();

        $data['totalCaseCount'] = count($casesCount);

        $totalData = DB::table('historico_processo AS hp')
                ->Join('processo AS p', 'p.id', '=', 'hp.processo_id')
                ->Join('juiz AS j', 'p.juiz_id', '=', 'j.id')
                ->select('j.*')
                ->whereDate('hp.bussiness_on_date', '>=', $date)
                ->whereDate('hp.bussiness_on_date', '<=', $date)
                ->when($checkTask['type'] == "ADV", function ($query) use($checkTask) {
                    $query->leftJoin('processo_membro AS pm', 'pm.processo_id', '=', 'p.id');
                    $query->where('pm.membro', $checkTask['id']);
                    return $query;
                })
                ->distinct()
                ->get();

        $res = array();
        if (count($totalData) > 0 && !empty($totalData))
         {
            $arrCourt = $totalData;

            foreach ($arrCourt as $key => $case_detail) {

                //$date = '2018-10-20';
                $court_case_ids = DB::table('case_logs AS cl')
                        ->where('judge_type', $case_detail->id)
                        ->where('bussiness_on_date', $date)
                        ->pluck('processo_id')
                        ->toArray();

                if (!empty($this->getcasesByIds($court_case_ids, $case_detail->id, $date))) {
                    $res[$key]['judge_name'] = $case_detail->nome;
                    $res[$key]['cases'] = $this->getcasesByIds($court_case_ids, $case_detail->id, $date);
                }
            }
        }

        $data['case_dashbord'] = $res;

        //user and its case counts
        $data['client'] = Cliente::count();
        $data['appointmentCount'] = Agenda::count();
//        $data['important_case'] = Processo::where('prioridade', 'Alta')
//                ->where('activo', 'S')
//                ->count();

        $data['case_total'] = DB::table('processo AS p')
                ->where('activo', 'S')
//                ->when($checkTask['type'] == "ADV", function ($query) use($checkTask) {
//                    $query->leftJoin('processo_membro AS pm', 'pm.processo_id', '=', 'p.id');
//                    $query->where('pm.membro', $checkTask['id']);
//                    return $query;
//                })
                ->count();


        $data['total_tarefas'] = DB::table('tarefa AS t')
                ->where('activo', 'S')
                ->when($checkTask['type'] == "ADV" || $checkTask['type'] == "User", function ($query) use($checkTask) {
                    $query->leftJoin('tarefa_membro AS tm', 'tm.tarefa_id', '=', 't.id');
                    $query->where('tm.membro_id', $checkTask['id']);
                    return $query;
                })
                ->count();

        $data['total_tarefas_hoje'] = DB::table('tarefa AS t')
                ->where('activo', 'S')
                ->where('inicio', date('Y-m-d'))
                ->when($checkTask['type'] == "ADV" || $checkTask['type'] == "User", function ($query) use($checkTask) {
                    $query->leftJoin('tarefa_membro AS tm', 'tm.tarefa_id', '=', 't.id');
                    $query->where('tm.membro_id', $checkTask['id']);
                    return $query;
                })
                ->count();

        $data['total_tarefas_futura'] = DB::table('tarefa AS t')
                ->where('activo', 'S')
                ->where('inicio','>', date('Y-m-d'))
                ->when($checkTask['type'] == "ADV" || $checkTask['type'] == "User", function ($query) use($checkTask) {
                    $query->leftJoin('tarefa_membro AS tm', 'tm.tarefa_id', '=', 't.id');
                    $query->where('tm.membro_id', $checkTask['id']);
                    return $query;
                })
                ->count();

        $data['total_agenda'] = Agenda::where('activo', 'OPEN')->count();

        $data['total_agenda_hoje'] = Agenda::where('activo', 'OPEN')
                                       ->where('data', date('Y-m-d'))
                                       ->count();

        $data['total_agenda_futura'] = Agenda::where('activo', 'OPEN')
                                       ->where('data', '>',date('Y-m-d'))
                                       ->count();

        $data['archived_total'] = Processo::where('activo', 'N')->count();

        $getAppointment = DB::table('agenda AS a')
                ->leftJoin('cliente AS c', 'c.id', '=', 'a.cliente_id')
                ->select('a.id AS id', 'a.activo AS status', 'a.data AS data', 'c.nome AS nome', 'a.nome AS appointment_name', 'c.nome AS first_name', 'c.sobrenome AS last_name', 'a.cliente_id AS client_id', 'a.type As type')
                ->where('a.activo', 'OPEN')
                ->where('a.data', date('Y-m-d'))
                ->get();
        $data['appoint_calander'] = $getAppointment;

        $data['caseStatuses'] = EstadoProcesso::where('activo', 'S')
                                                ->get();

        $data['judges'] = Juiz::where('activo', 'S')->get();


        return view('admin.index', $data);
    }

    public function ajaxCalander(Request $request)
    {

        $checkTask = LogActivity::CheckuserType();

        $CourtCase = DB::table('historico_processo AS hp')
                ->join('processo AS p', 'hp.processo_id', '=', 'p.id')
                ->select('p.id as id', 'hp.bussiness_on_date as start')
                ->when($checkTask['type'] == "ADV", function ($query) use($checkTask) {
                    $query->Join('processo_membro AS pm', 'pm.processo_id', '=', 'p.id');
                    $query->where('pm.membro', $checkTask['id']);
                    return $query;
                })
                ->whereMonth('hp.bussiness_on_date', $request->start)
                ->whereYear('hp.bussiness_on_date', $request->end)
                ->orWhereYear('hp.bussiness_on_date', $request->start)
                ->orWhereYear('hp.bussiness_on_date', $request->end)
                ->get();

        if (!empty($CourtCase))
        {
            foreach ($CourtCase as $value1) {
                $value1->color = '#27c24c';
                $value1->refer = "case";
                $value1->start = date('Y-m-d', strtotime($value1->start));
            }
        }
        // dd($CourtCase);
        //calander
        $appointment = Agenda::select('cliente_id', 'type', 'id', 'nome AS title', 'created_at as color', DB::raw('DATE_FORMAT(data, "%d-%m-%Y") as start'))
                ->where('activo', 'OPEN')
                ->whereMonth('data', $request->start)
                ->whereYear('data', $request->end)
                ->orWhereYear('data', $request->start)
                ->orWhereYear('data', $request->end)
                ->get();

        if (!empty($appointment))
        {

            foreach ($appointment as $value) {
                if ($value->type == "exists") {
                    $client = Cliente::where('id', $value->cliente_id)->first();

                    $value->title = $client->full_name;
                }
                $value->refer = "appointment";
                $value->color = '#f05050';

                unset($value->cliente_id);
                unset($value->type);
            }
        }

        $CourtCase = collect($CourtCase)->toArray();
        $appointment = collect($appointment)->toArray();
        // dd($CourtCase,$appointment);
        $merg = array_merge($CourtCase, $appointment);


        return collect($merg)->ToJson();
    }

    public function downloadCaseBoard($date)
    {
        $data['setting'] = ConfiguracaoGeral::where('id', "1")->first();

        //filter by date
        $date = $date;

        $data['date'] = $date;
        if (isset($date) && !empty($date)) {
            $date = date('Y-m-d', strtotime($date));
            $data['date'] = $date;
        }
        //get login user id
        $data['associatedName'] = Admin::select('associated_name')->first();
        $casesCount = DB::table('processo AS case')
                        ->leftJoin('case_logs AS cl', 'cl.court_case_id', '=', 'case.id')
                        ->leftJoin('cliente AS ac', 'ac.id', '=', 'case.cliente_id')
                        ->leftJoin('tipoprocesso AS ct', 'ct.id', '=', 'case.case_types')
                        ->leftJoin('estadoprocesso AS s', 's.id', '=', 'case.case_status')
                        ->select('case.id AS case_id', 'cl.bussiness_on_date', 'cl.hearing_date', 'case.client_position', 'case.party_name', 'case.party_lawyer', 'ct.case_type_name AS caseType', 's.case_status_name', 'ac.nome', 'ac.sobrenome', 'ac.instituicao'
                        )
                        ->where('case.activo', 'S')
                        ->where('cl.bussiness_on_date', $date)
                        ->distinct()
                        ->get()->toArray();

        $data['totalCaseCount'] = count($casesCount);

        $totalData = DB::table('case_logs AS cl')
                ->Join('juiz AS j', 'j.id', '=', 'cl.judge_type')
                ->Join('processo AS case', 'case.id', '=', 'cl.court_case_id')
                ->where('case.is_nb', 'No')
                ->select('cl.judge_type', 'j.judge_name')
                ->whereDate('cl.bussiness_on_date', '>=', $date)
                ->whereDate('cl.bussiness_on_date', '<=', $date)
                ->distinct()
                ->get();

        $res = array();
        if (count($totalData) > 0 && !empty($totalData)) {
            $arrCourt = $totalData;
            foreach ($arrCourt as $key => $case_detail) {
                $court_case_ids = CaseLog::where('judge_type', $case_detail->judge_type)->where('bussiness_on_date', $date)->pluck('court_case_id')->toArray();
                if (!empty($this->getcasesByIds($court_case_ids, $case_detail->judge_type, $date))) {
                    $res[$key]['judge_name'] = $case_detail->judge_name;
                    $res[$key]['caseCourt'] = count($this->getcasesByIds($court_case_ids, $case_detail->judge_type, $date));
                    $res[$key]['cases'] = $this->getcasesByIds($court_case_ids, $case_detail->judge_type, $date);
                }
            }
        }
        $data['case_dashbord'] = $res;
        //dd($data['case_dashbord']);
        //pdf download
        $pdf = PDF::loadView('pdf.case-board', $data);
        $filename = 'Case Board Of-' . $date . ".pdf";
        return $pdf->download($filename);
    }

    public function printCaseBoard($date)
    {

        //filter by date
        $date = $date;
        $data['date'] = $date;
        if (isset($date) && !empty($date)) {
            $date = date('Y-m-d', strtotime(LogActivity::commonDateFromat($date)));

            $data['date'] = $date;
        }
        //get login user id

        $data['setting'] = ConfiguracaoGeral::where('id', "1")->first();

        $data['associatedName'] = Admin::select('associated_name')->where('id', "1")->first();

        $casesCount = DB::table('processo AS p')
                        ->leftJoin('case_logs AS cl', 'cl.court_case_id', '=', 'p.id')
                        ->leftJoin('cliente AS ac', 'ac.id', '=', 'p.cliente_id')
                        ->leftJoin('tipoprocesso AS ct', 'ct.id', '=', 'p.case_types')
                        ->leftJoin('tipoprocesso AS cst', 'cst.id', '=', 'p.case_sub_type')
                        ->leftJoin('estadoprocesso AS s', 's.id', '=', 'p.case_status')
                        ->select('p.id AS case_id', 'cl.bussiness_on_date', 'cl.hearing_date', 'p.client_position', 'p.party_name', 'p.party_lawyer', 'p.registration_number', 'p.judge_name', 'ct.case_type_name AS caseType', 'cst.case_type_name AS caseSubType', 's.case_status_name', 'ac.nome', 'ac.sobrenome', 'ac.instituicao'
                        )
                        ->where('p.activo', 'S')
                        ->where('p.is_nb', 'No')
                        ->where('cl.bussiness_on_date', $date)
                        ->distinct()
                        ->get()->toArray();
        $data['totalCaseCount'] = count($casesCount);

        $totalData = DB::table('case_logs AS cl')
                ->Join('juiz AS j', 'j.id', '=', 'cl.judge_type')
                ->Join('processo AS case', 'case.id', '=', 'cl.court_case_id')
                ->where('case.is_nb', 'No')
                ->select('cl.judge_type', 'j.nome')
                ->whereDate('cl.bussiness_on_date', '>=', $date)
                ->whereDate('cl.bussiness_on_date', '<=', $date)
                ->distinct()
                ->get();

        $res = array();
        if (count($totalData) > 0 && !empty($totalData)) {
            $arrCourt = $totalData;
            foreach ($arrCourt as $key => $case_detail) {

                $court_case_ids = CaseLog::where('judge_type', $case_detail->judge_type)
                        ->where('bussiness_on_date', $date)
                        ->pluck('court_case_id')
                        ->toArray();

                if (!empty($this->getcasesByIds($court_case_ids, $case_detail->judge_type, $date))) {
                    $res[$key]['judge_name'] = $case_detail->judge_name;
                    $res[$key]['caseCourt'] = count($this->getcasesByIds($court_case_ids, $case_detail->judge_type, $date));
                    $res[$key]['cases'] = $this->getcasesByIds($court_case_ids, $case_detail->judge_type, $date);
                }
            }
        }
        $data['case_dashbord'] = $res;

        //pdf download
        $pdf = PDF::loadView('pdf.case-board', $data);
        return $pdf->stream();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    public function appointmentList(Request $request)
    {

        $date = date('Y-m-d');
        if (isset($request->appoint_date) && !empty($request->appoint_date)) {
            $date = date('Y-m-d', strtotime(LogActivity::commonDateFromat($request->appoint_date)));
        }

        $columns = array(
            0 => 'id',
            1 => 'name',
            2 => 'date',
            2 => 'time',
        );


        $totalData = DB::table('agenda AS a')
                ->leftJoin('cliente AS ac', 'ac.id', '=', 'a.cliente_id')
                ->select('a.id AS id', 'a.activo AS status', 'a.telefone AS mobile', 'a.data AS date', 'a.hora AS app_time', 'c.nome AS name', 'a.nome AS appointment_name', 'ac.nome AS first_name', 'ac.sobrenome AS last_name', 'a.cliente_id AS client_id', 'a.type As type')
                ->whereDate('data', '>=', $date)
                ->whereDate('data', '<=', $date)
                ->count();
        $totalRec = $totalData;
        // $totalData = DB::table('appointments')->count();

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $search = $request->input('search.value');
        $terms = DB::table('agenda AS a')
                ->leftJoin('cliente AS ac', 'ac.id', '=', 'a.cliente_id')
                ->select('a.id AS id', 'a.activo AS status', 'a.telefone AS mobile', 'a.data AS date', 'a.hora AS app_time', 'ac.nome AS name','ac.instituicao', 'a.nome AS appointment_name', 'ac.nome AS first_name', 'ac.sobrenome AS last_name', 'a.cliente_id AS client_id', 'a.type As type')
                ->whereDate('data', '>=', $date)
                ->whereDate('data', '<=', $date)
                ->where(function ($query) use ($search) {
                    return $query->where('a.telefone', 'LIKE', "%{$search}%")
                            ->orWhere('a.nome', 'LIKE', "%{$search}%")
                            ->orWhere('ac.nome', 'LIKE', "%{$search}%")
                            ->orWhere('ac.sobrenome', 'LIKE', "%{$search}%")
                            ->orWhere('a.activo', 'LIKE', "%{$search}%")
                            ->orWhereRaw("concat(ac.nome, ' ', ac.sobrenome) like '%{$search}%' ");
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

        $totalFiltered = $terms->count();

        $data = array();
        if (!empty($terms)) {

            foreach ($terms as $term) {

                $nestedData['id'] = $term->id;
                $nestedData['date'] = date(LogActivity::commonDateFromatType(), strtotime($term->date));
                $nestedData['time'] = date('g:i a', strtotime($term->app_time));
                $nestedData['mobile'] = $term->mobile;
                if ($term->type == "new") {
                    $nestedData['name'] =($term->instituicao)? $term->instituicao : $term->name . ' ' . $term->last_name;
                } else {
                    $nestedData['name'] = $term->first_name . ' ' . $term->last_name;
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

}
