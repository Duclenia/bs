<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\TaskRequest;
use App\Http\Controllers\Controller;
use App\Models\{Cliente,Processo,Tarefa,TarefaMembro};
use App\Traits\DatatablTrait;
use App\Models\ClienteParte;
use DB;
use App\Models\Admin;
use App\Helpers\LogActivity;
use DateTime;

class TarefaController extends Controller
{

    use DatatablTrait;
    
    private $admin;
    private $tarefa;
    
    public function __construct(Admin $admin, Tarefa $tarefa)
    {  
        $this->admin = $admin;
        $this->tarefa = $tarefa;
        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Gate::denies('task_list'))
            return back();

        return view('admin.tarefa.task');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Gate::denies('task_add'))
            return back();

        $this->data['users'] = $this->admin->listarMembros();

        return view('admin.tarefa.task_create', $this->data);
    }

    public function getCaseDetail($id)
    {
        
        $t = DB::table('processo AS p')
                ->leftJoin('cliente AS cl', 'cl.id', '=', 'p.cliente_id')
                ->leftJoin('tipoprocesso AS tp', 'tp.id', '=', 'p.tipoprocesso_id')
                ->leftJoin('estadoprocesso AS s', 's.id', '=', 'p.estado')
                ->leftJoin('tribunal AS t', 't.id', '=', 'p.tribunal_id')
                ->leftJoin('juiz AS j', 'j.id', '=', 'p.juiz_id')
                ->select('p.id AS id','p.no_interno' ,'p.no_processo AS case_number', 'p.prioridade', 's.estado', 'cl.nome', 'cl.sobrenome','cl.instituicao', 'cl.tipo AS tipo_cliente' ,'p.updated_by', 'cl.id AS advo_client_id', 'p.activo'
                )
                ->where('p.id', $id)
                ->first();

        return $t;
    }

    public function getMembers($id)
    {
        $getTaskMemberArr = TarefaMembro::where('tarefa_id', $id)->pluck('membro_id');
        // dd( $getTaskMemberArr );
        $getmulti = Admin::whereIn('id', $getTaskMemberArr)->get();

        $con = "<div style='display: flex;''>";
        foreach ($getmulti as $key => $value) {
            $con .= '<div title="' . htmlspecialchars($value->pessoasingular->nome) . ' ' . htmlspecialchars($value->pessoasingular->sobrenome) . '" data-letters="' . ucfirst(substr($value->pessoasingular->nome, 0, 1)) . '"> </div>';
        }
        $con .= "</div>";

        return $con;
    }

    public function TaskList(Request $request)
    {

        $user = auth()->user();
        $isEdit = $user->can('task_edit');
        $isDelete = $user->can('task_delete');

        $columns = array(
            0 => 'id',
            1 => 'task_subject',
            3 => 'start_date',
            4 => 'hora_inicio',
            5 => 'hora_termino'
        );

        $totalData = DB::table('tarefa AS task')->count();
        $totalRec = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $customcollections = DB::table('tarefa AS task')
                ->when($search, function ($query, $search) {
            return $query->where('task_subject', 'LIKE', "%{$search}%");
        });

        $totalFiltered = $customcollections->count();

        $customcollections = $customcollections->offset($start)->limit($limit)->orderBy($order, $dir)->get();

        $data = [];

        foreach ($customcollections as $key => $item) {

            $show = route('clients.show', $item->id);

            // $row['id'] = $item->id;
            if (empty($request->input('search.value'))) {
                $final = $totalRec - $start;
                $row['id'] = $final;
                $totalRec--;
            } else {
                $start++;
                $row['id'] = $start;
            }

            $row['task_subject'] = htmlspecialchars($item->task_subject);
            if ($item->rel_id != 0) {
                $val = $this->getCaseDetail($item->rel_id);
                
                $clientName = $val->tipo_cliente == 2 ? $val->nome. ' '.$val->sobrenome : $val->instituicao;

                $case = '<b> ' . htmlspecialchars($clientName). '</b>
                      <p>N&ordm; interno :<b> ' . str_pad($val->no_interno, 7, '0', STR_PAD_LEFT). 'HCAC</b></p>';
               
                $row['case'] = $case;
            } else {

                $row['case'] = "Other";
            }

            $row['start_date'] = date('d-m-Y', strtotime($item->inicio));
            $row['hora_inicio'] = date('g:i a', strtotime($item->hora_inicio));
            $row['hora_termino'] = date('g:i a', strtotime($item->hora_termino));

            $row['members'] = $this->getMembers($item->id);

            $taskStatus = $item->project_status;

            $lableColor = '';
            $status = "";

            if ($taskStatus == 'pendente') {
                $status = "Pendente";
                $lableColor = 'label label-primary';
            } elseif ($taskStatus == 'em curso') {
                $status = "Em curso";
                $lableColor = 'label label-info';
            } elseif ($taskStatus == 'concluída') {
                $status = "Concluída";
                $lableColor = 'label label-success';
            } 

            $row['status'] = "<span class='" . $lableColor . "'>" . $status . "</span>";

            $taskPriority = $item->prioridade;
            $lableColor = '';

            if ($taskPriority == 'Baixa') {

                $lableColor = 'label label-primary';
            } elseif ($taskPriority == 'Média') {

                $lableColor = 'label label-info';
            } elseif ($taskPriority == 'Alta') {

                $lableColor = 'label label-danger';
            } elseif ($taskPriority == 'Urgente') {

                $lableColor = 'label label-danger';
            }

            $row['priority'] = "<span class='" . $lableColor . "'>" . $taskPriority . "</span>";

            // $row['status'] ="yiyui";

            if ($isEdit == "1" || $isDelete == "1") {
                $row['action'] = $this->action([
                    'edit' => route('tarefas.edit', encrypt($item->id)),
                    'delete' => collect([
                        'id' => $item->id,
                        'action' => route('tarefas.destroy', encrypt($item->id)),
                    ]),
                    'edit_permission' => $isEdit,
                    'delete_permission' => $isDelete,
                ]);
            } else {
                $row['action'] = [];
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\TaskRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TaskRequest $request)
    {

        $tarefa = new Tarefa();
        if ($request->related == '' || $request->related == 'other') {
            $tarefa->rel_type = 'other';
            $tarefa->rel_id = 0;
        } else {
            $tarefa->rel_type = $request->related;
            $tarefa->rel_id = $request->related_id;
        }

        // $task->project_type_task_id = $project_type_task_id;
        $tarefa->task_subject = $request->task_subject;
        $tarefa->project_status = $request->project_status_id;
        $tarefa->prioridade = $request->priority;
        $tarefa->inicio = date('Y-m-d H:i:s', strtotime(LogActivity::commonDateFromat($request->start_date)));  //date('Y-m-d', strtotime($request->start_date));
        $tarefa->hora_inicio = date('H:i:s', strtotime($request->hora_inicio)); ;
        $tarefa->hora_termino = date('H:i:s', strtotime($request->hora_termino));
        $tarefa->descricao = $request->task_description;
        $tarefa->save();


        foreach ($request->assigned_to as $key => $value) {
            # Arrary in assigne employee...
            $tarefaMembro = new TarefaMembro();
            $tarefaMembro->tarefa_id = $tarefa->id;
            $tarefaMembro->membro_id = $value;
            $tarefaMembro->save();
        }
        
        $membros = $tarefa->membros;
            
            $this->tarefa->notificacao($membros, $tarefa);

        return redirect()->route('tarefas.index')->with('success', "Tarefa criada.");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
        $data['single'] = array();
        $data['multiple'] = array();
        $data['client'] = Cliente::find($id);
        $data['single'] = ClienteParte::where('cliente_id', $id)->get();

        $clientName = Cliente::findorfail($id);
        $data['name'] = $clientName->full_name;

        return view('admin.cliente.view.client_detail', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = auth()->user();
        
        if (!$user->can('task_edit'))
            return back();
        
        $this->data['task'] = tarefa::findOrFail(decrypt($id));
        // dd(   $this->data['task']);
        $this->data['users'] = $this->admin->listarMembros();

        $this->data['cases'] = DB::table('processo AS p')
                ->leftJoin('cliente AS ac', 'ac.id', '=', 'p.cliente_id')
                ->leftJoin('tipoprocesso AS ct', 'ct.id', '=', 'p.tipoprocesso_id')
                ->leftJoin('estadoprocesso AS s', 's.id', '=', 'p.estado')
                ->leftJoin('tribunal AS t', 't.id', '=', 'p.tribunal_id')
                ->leftJoin('juiz AS j', 'j.id', '=', 'p.juiz_id')
                ->select('p.id AS id', 'p.no_processo','p.no_interno','p.prioridade', 's.estado', 'ac.nome', 'ac.sobrenome', 'ac.instituicao', 'ac.tipo AS tipo_cliente', 'p.updated_by', 'ac.id AS advo_client_id', 'p.activo'
                )
                ->where('p.activo', 'S')
                ->where('p.id', $this->data['task']->rel_id)
                ->get();
        
        $this->data['user_ids'] = array();

        $this->data['user_ids'] = TarefaMembro::where('tarefa_id', decrypt($id))
                                              ->pluck('membro_id')
                                              ->toArray();
    
        return view('admin.tarefa.task_edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\TaskRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TaskRequest $request, $id)
    {

        $tarefa = Tarefa::findorfail(decrypt($id));
        if ($request->related == '' || $request->related == 'other') {
            $tarefa->rel_type = 'other';
            $tarefa->rel_id = 0;
            $getTaskMember = TarefaMembro::select('id')
                                           ->where('tarefa_id', decrypt($id))
                                           ->delete();
        } else {
            $tarefa->rel_type = $request->related;
            $tarefa->rel_id = $request->related_id;
        }

        // $task->project_type_task_id = $project_type_task_id;
        $tarefa->task_subject = $request->task_subject;
        $tarefa->project_status = $request->project_status_id;
        $tarefa->prioridade = $request->priority;
        $tarefa->inicio = date('Y-m-d', strtotime($request->start_date));
        $tarefa->hora_inicio = date('H:i:s', strtotime($request->hora_inicio));
        $tarefa->hora_termino = date('H:i:s', strtotime($request->hora_termino));
        $tarefa->descricao = $request->task_description;
        $tarefa->save();

        $getTaskMember = TarefaMembro::select('id')
                                          ->where('tarefa_id', $id)
                                          ->delete();

        foreach ($request->assigned_to as $key => $value) {
            # Arrary in assigne employee...
            $tarefaMembro = new TarefaMembro();
            $tarefaMembro->tarefa_id = $tarefa->id;
            $tarefaMembro->membro_id = $value;
            $tarefaMembro->save();
        }

        return redirect()->route('tarefas.index')->with('success', "Tarefa actualizada.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        $tarefa = Tarefa::find(decrypt($id));
        $tarefa->delete();

        TarefaMembro::where('tarefa_id', decrypt($id))->delete();
        
        return response()->json([
                    'success' => true,
                    'message' => 'Tarefa eliminada.',
                        ], 200);

        //return redirect()->route('tasks.index')->with('success',"Task deleted successfully.");
    }

    public function changeStatus(Request $request) {

        $statuscode = 400;
        $cliente = Cliente::findOrFail($request->id);
        $cliente->activo = $request->status == 'true' ? 'S' : 'N';

        if ($cliente->save()) {
            $statuscode = 200;
        }
        $status = $request->status == 'true' ? 'active' : 'deactivate';
        $message = 'Client status ' . $status . ' successfully.';

        return response()->json([
                    'success' => true,
                    'message' => $message
                        ], $statuscode);
    }

    public function check_client_email_exits(Request $request)
    {
        if ($request->id == "")
        {
            $count = Cliente::where('email', $request->email)->count();
            if ($count == 0) {
                return 'true';
            } else {
                return 'false';
            }
        } else {
            $count = Cliente::where('email', '=', $request->email)
                    ->where('id', '<>', $request->id)
                    ->count();
            if ($count == 0) {
                return 'true';
            } else {
                return 'false';
            }
        }
    }

    public function caseDetail($id) {

        //$advocate_id = $this->getLoginUserId();
        $totalCourtCase = Processo::where('cliente_id', $id)->count();

        $clientName = Cliente::findorfail($id);
        $name = $clientName->primeiro_nome . ' ' . $clientName->nome_meio . ' ' . $clientName->sobrenome;
        $client = Cliente::find($id);
        return view('admin.cliente.view.cases_view', ['advo_client_id' => $id, 'name' => $name, 'totalCourtCase' => $totalCourtCase, 'client' => $client]);
    }

    public function accountDetail($id)
    {
        // $advocate_id = $this->getLoginUserId();
        $clientName = Cliente::findorfail($id);
        $name = $clientName->primeiro_nome . ' ' . $clientName->nome_meio . ' ' . $clientName->sobrenome;
        $client = Cliente::find($id);
        return view('admin.cliente.view.client_account', ['advo_client_id' => $id, 'name' => $name, 'client' => $client]);
    }
    
   public function getNotificacao()
   {
       $checkTask = LogActivity::CheckuserType();
       
       $tarefas = DB::table('tarefa_membro AS tm')
                ->join('tarefa AS t', 'tm.tarefa_id', '=', 't.id')
                ->leftJoin('processo AS p', 't.rel_id', '=', 'p.id')
                ->select('t.inicio AS data_inicio', 't.task_subject AS assunto','t.rel_type AS relacionada_a' ,'p.no_interno')
                ->where('t.project_status', 'pendente')
                ->when($checkTask['type'] == "User", function ($query) use ($checkTask) {
                    $query->where('tm.membro_id', $checkTask['id']);
                    return $query;
                })
                ->get();
                
//        foreach($tarefas as $tarefa)
//        {
//            $data_actual = new DateTime('now');
//            
//            $inicio_tarefa = new DateTime($tarefa->data_inicio);
//            
//            $intervalo = $inicio_tarefa->diff($data_actual);
//            
//            if($intervalo->d >= 1 && $intervalo->d <= 5)
//            {
//                
//                
//                
//            }
//            
//        }
                
        return response()->json($tarefas);
   }

}
