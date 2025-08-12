<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Traits\DatatablTrait;
use App\Http\Controllers\Controller;
use Validator;
use App\Models\Plano;
use App\Models\Subscricao;
use App\Helpers\LogActivity;
use DateTime;
use DB;

class SubscricaoController extends Controller
{
    use DatatablTrait;
    
    private $plano;
    private $subscricao;
    
    public function __construct(Plano $plano, Subscricao $subscricao) {
        
        $this->plano = $plano;
        $this->subscricao = $subscricao;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.subscricao.index');
    }
    
    public function subscricaoList(Request $request)
    {
        $user = auth()->user();
        $isEdit = $user->can('editar_subscricao');
        $isDelete = $user->can('subscricao_delete');

        // Listing column to show
        $columns = array(
            0 => 'id',
            1 => 'plano',
            2 => 'data_inicio',
            3 => 'data_termino',
            4 =>  'periodicidade',
            5 =>  'processos_registados',
            6 =>  'total_processos',
            7 =>  'estado',
            8 => 'action'
        );
        
        $totalData = DB::table('subscricao AS s')
                         ->leftJoin('plano AS p', 'p.id', '=', 's.plano_id')
                         ->select('s.*', 'p.nome AS plano')
                         ->count();
        $totalRec = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');
        
        $customcollections = DB::table('subscricao AS s')
                                ->leftJoin('plano AS p', 'p.id', '=', 's.plano_id')
                                ->select('s.*', 'p.nome AS plano')
                                ->when($search, function ($query, $search) {
                                    return $query->where('p.nome', 'LIKE', "%{$search}%")
                                                 ->orWhere('s.data_inicio', 'LIKE', "%{$search}%")
                                                 ->orWhere('s.data_termino', 'LIKE', "%{$search}%");
        });

        $totalFiltered = $customcollections->count();

        $customcollections = $customcollections->offset($start)->limit($limit)->orderBy($order, $dir)->get();

        $data = [];
        
        foreach ($customcollections as $key => $item) {

            // $row['id'] = $item->id;

            if (empty($request->input('search.value'))) {
                $final = $totalRec - $start;
                $row['id'] = $final;
                $totalRec--;
            } else {
                $start++;
                $row['id'] = $start;
            }
            
            $periodicidade = '';
            
            switch($item->periodicidade):
                case 'A':
                    $periodicidade = 'Anual'; break;
                case 'M':
                    $periodicidade = 'Mensal'; break;
                case 'T':
                    $periodicidade = 'Trimestral'; break;
            endswitch;
            
            $row['plano'] = htmlspecialchars($item->plano);
            $row['data_inicio'] = date('d-m-Y', strtotime($item->data_inicio));
            $row['data_termino'] = date('d-m-Y', strtotime($item->data_termino));
            $row['periodicidade'] = $periodicidade;
            $row['processos_registados'] = $item->processo_registado;
            $row['total_processos'] = $item->total_processo;
            
            $lableColor = '';
            $status = "";

            if (verificarData($item->data_termino)) {
                $estado = "Activa";
                $lableColor = 'label label-primary';
                
            }else{
                $estado = "Vencida";
                $lableColor = 'label label-danger';
            } 

            $row['estado'] = "<span class='" . $lableColor . "'>" . $estado . "</span>";
            
     
            if ($isEdit == "1" || $isDelete == "1") {

                $row['action'] = $this->action([
                    'edit_modal' => collect([
                        'id' => $item->id,
                        'action' => route('subscricao.edit', $item->id),
                        'target' => '#addtag'
                    ]),
                    'edit_permission' => $isEdit,
                    'delete' => collect([
                        'id' => $item->id,
                        'action' => route('subscricao.destroy', $item->id),
                    ]),
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $planos = $this->plano->all();

        return response()->json([
                    'html' => view('admin.subscricao.create', compact('planos'))->render()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
                    'plano' => 'required',
                    'periodicidade' => 'required',
                    'data_inicio' => 'required'
        ]);
        
        $subcr = DB::table('subscricao')
                ->where('data_termino', function($query) {
                    $query->selectRaw('MAX(data_termino)')
                    ->from('subscricao');
                })
                ->first();

        if ($validator->fails())
            return response()->json(['errors' => $validator->errors()->all()]);
        
        $plano = $this->plano->findOrFail($request->plano);
        
        $total_meses = 1;
        
        switch($request->periodicidade):
            case 'A':
                $total_meses = 12; break;
            case 'M':
                $total_meses = 1; break;
            case 'T':
                $total_meses = 3; break;
        endswitch;
        
        $totalProcesso = $plano->total_processo * $total_meses;
        
        if($subcr)
        {
            $processo_restante = $subcr->total_processo - $subcr->processo_registado;
                
            $total_processo = ($subcr->total_processo > $subcr->processo_registado) ? $totalProcesso + $processo_restante : $totalProcesso;
        }else{
            
            $total_processo = $totalProcesso;
        }
        
        $data_inicio = date('Y-m-d H:i:s', strtotime(LogActivity::commonDateFromat($request->data_inicio)));
        
        $inicio = explode('-',$data_inicio);
        
        $ano = (int)$inicio[0];
        $mes = (int)$inicio[1];
        $dia = (int)$inicio[2];
        
        $date = new DateTime();
        $date->setDate($ano,$mes,$dia);
        
        $data_termino = add_months($date, $total_meses);
        
        $subscricao = $this->subscricao->create(['plano_id' => $request->plano,
                                       'data_inicio' => $data_inicio,
                                       'data_termino' => $data_termino,
                                       'periodicidade' => $request->periodicidade,
                                       'processo_registado' => 0,
                                       'total_processo' => $total_processo
                                      ]);
        if ($subscricao) {
            
            return response()->json([
                        'success' => true,
                        'message' => 'Subscrição registada',
                            ], 200);
        }
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
        $dados['subscricao'] = $this->subscricao->findOrFail($id);
        
        $dados['planos'] = $this->plano->all();
        
        return response()->json([
                    'html' => view('admin.subscricao.edit', $dados)->render()
        ]);
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
        $validator = Validator::make($request->all(), [
                    'plano' => 'required',
                    'periodicidade' => 'required', 
                    'data_inicio' => 'required'
        ]);

        if ($validator->fails())
            return response()->json(['errors' => $validator->errors()->all()]);
        
        $plano = $this->plano->findOrFail($request->plano);
        
        $total_meses = 1;
        
        switch($request->periodicidade):
            case 'A':
                $total_meses = 12; break;
            case 'M':
                $total_meses = 1; break;
            case 'T':
                $total_meses = 3; break;
        endswitch;
        
        $data_inicio = date('Y-m-d H:i:s', strtotime(LogActivity::commonDateFromat($request->data_inicio)));
        
        $subscricao = $this->subscricao->findorfail($id);
        
        $totalProcesso = $plano->total_processo * $total_meses;
        
        if($id > 1){
            
            $subsc = $this->subscricao->findOrFail(--$id);
                    
            $total_processo = ($subsc->total_processo > $subsc->processo_registado) ? 
                            $totalProcesso + ($subsc->total_processo - $subsc->processo_registado) : $totalProcesso;
        }else{
            
            $total_processo = $totalProcesso;
        }
        
        
        $inicio = explode('-',$data_inicio);
        
        $ano = (int)$inicio[0];
        $mes = (int)$inicio[1];
        $dia = (int)$inicio[2];
        
        $date = new DateTime();
        $date->setDate($ano,$mes,$dia);
        
        $data_termino = add_months($date, $total_meses);
        
        $subscricao->data_inicio = $data_inicio;
        $subscricao->data_termino = $data_termino;
        $subscricao->periodicidade = $request->periodicidade;
        $subscricao->total_processo = $total_processo;
        $subscricao->plano_id = $request->plano;
        $subscricao->save();
        
        if($subscricao->save()){
            
            return response()->json([
                    'success' => true,
                    'message' => 'Dados actualizados',
                        ], 200);
        }   
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
