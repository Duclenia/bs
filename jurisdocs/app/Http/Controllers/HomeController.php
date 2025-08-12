<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Cliente,Processo,EstadoProcesso,Juiz,Agenda};
use DB;
use App\Helpers\LogActivity;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $checkTask = LogActivity::CheckuserType();
        //filter by date
        $date = date('Y-m-d');

        $data['date'] = $date;

        if (isset($request->client_case) && !empty($request->client_case))
        {
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
                        ->select('p.id AS case_id', 'hp.bussiness_on_date', 'hp.hearing_date', 'p.client_position', 'p.party_name', 'p.party_lawyer', 'tp.designacao AS caseType', 'ep.estado', 'c.nome', 'c.sobrenome', 'c.instituicao')
                        // ->where('case.advocate_id',$advocate_id)
                        ->where('p.activo', 'S')
                        ->where('hp.bussiness_on_date', $date)
                        ->when($checkTask['type'] == "User", function ($query) use($checkTask) {
                            $query->leftJoin('processo_membro AS pm', 'pm.processo_id', '=', 'p.id');
                            $query->where('pm.membro', $checkTask['id']);
                            return $query;
                        })
                        ->distinct()
                        ->get()->toArray();


        $data['totalCaseCount'] = count($casesCount);


        $totalData = DB::table('historico_processo AS hp')
                ->Join('processo AS p', 'p.id', '=', 'hp.processo_id')
                ->Join('juiz AS j', 'p.juiz_id', '=', 'j.id')
                ->select('j.nome')
                ->whereDate('hp.bussiness_on_date', '>=', $date)
                ->whereDate('hp.bussiness_on_date', '<=', $date)
                ->when($checkTask['type'] == "User", function ($query) use($checkTask) {
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

            foreach ($arrCourt as $key => $case_detail)
            {
                //$date = '2018-10-20';
                $court_case_ids = DB::table('case_logs AS cl')
                        ->where('judge_type', $case_detail->judge_type)
                        ->where('bussiness_on_date', $date)
                        ->pluck('processo_id')
                        ->toArray();

                if (!empty($this->getcasesByIds($court_case_ids, $case_detail->judge_type, $date)))
                {
                    $res[$key]['judge_name'] = $case_detail->judge_name;
                    $res[$key]['cases'] = $this->getcasesByIds($court_case_ids, $case_detail->judge_type, $date);
                }
            }
        }

        $data['case_dashbord'] = $res;

        //user and its case counts
        $data['client'] = Cliente::count();
        $data['appointmentCount'] = Agenda::count();
//        $data['important_case'] = Processo::where('prioridade', 'High')
//                ->where('activo', 'S')
//                ->count();

        $data['case_total'] = DB::table('processo AS p')
                ->where('activo', 'S')
                ->when($checkTask['type'] == "User", function ($query) use($checkTask) {
                    $query->leftJoin('processo_membro AS pm', 'pm.processo_id', '=', 'p.id');
                    $query->where('pm.membro', $checkTask['id']);
                    return $query;
                })
                ->count();

        $data['total_tarefas'] = DB::table('tarefa AS t')
                ->where('activo', 'S')
                ->when($checkTask['type'] == "User", function ($query) use($checkTask) {
                    $query->leftJoin('tarefa_membro AS tm', 'tm.tarefa_id', '=', 't.id');
                    $query->where('tm.membro_id', $checkTask['id']);
                    return $query;
                })
                ->count();

        $data['total_tarefas_hoje'] = DB::table('tarefa AS t')
                ->where('activo', 'S')
                ->where('inicio', date('Y-m-d'))
                ->when($checkTask['type'] == "User", function ($query) use($checkTask) {
                    $query->leftJoin('tarefa_membro AS tm', 'tm.tarefa_id', '=', 't.id');
                    $query->where('tm.membro_id', $checkTask['id']);
                    return $query;
                })
                ->count();

        $data['total_tarefas_futura'] = DB::table('tarefa AS t')
                ->where('activo', 'S')
                ->where('inicio', '>', date('Y-m-d'))
                ->when($checkTask['type'] == "User", function ($query) use($checkTask) {
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
                ->where('data', '>', date('Y-m-d'))
                ->count();

        $data['archived_total'] = Processo::where('activo', 'N')->count();

        $getAppointment = DB::table('agenda AS a')
                ->leftJoin('cliente AS c', 'c.id', '=', 'a.cliente_id')
                ->select('a.id AS id', 'a.activo AS status', 'a.data AS data', 'a.nome AS nome', 'a.nome AS appointment_name', 'c.nome AS first_name', 'c.sobrenome AS last_name', 'a.cliente_id AS client_id', 'a.type As type')
                ->where('a.activo', 'OPEN')
                ->where('a.data', date('Y-m-d'))
                ->get();
        $data['appoint_calander'] = $getAppointment;


//        $data['caseTypes'] = TipoProcesso::where('parent_id', 0)
//                ->where('activo', 'S')
//                ->orderBy('created_at', 'desc')
//                ->get();

        $data['caseStatuses'] = EstadoProcesso::where('activo', 'S')
                ->get();

        $data['judges'] = Juiz::where('activo', 'S')->get();

        return view('admin.index', $data);
    }
    
    public function notificacoes()
    {
        return view('notificacoes');
    }

    public function markNotification(Request $request)
    {
        auth()->user()
                ->unreadNotifications
                ->when($request->input('id'), function ($query) use ($request) {
                    return $query->where('id', $request->input('id'));
                })
                ->markAsRead();

        return response()->noContent();
    }
}
