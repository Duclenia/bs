<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\Traits\DatatablTrait;
use DB;
use App\Models\AreaProcessual;
use App\Models\IntervenienteDesignacao;
use App\Models\AreaprocessualIntervdesignacao;

class IntervDesignacaoController extends Controller
{
    use DatatablTrait;
    
    private $areaprocessual;
    private $intervdesignacao;
    
    public function __construct(AreaProcessual $areaprocessual, IntervenienteDesignacao $intervdesignacao) {
        
        $this->areaprocessual = $areaprocessual;
        
        $this->intervdesignacao = $intervdesignacao;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();

        if (!$user->can('listar_intervdesignacao'))
            return redirect()->back();

        return view('admin.configuracoes.intervdesignacao.designacao');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dado['areasprocessuais'] = $this->areaprocessual->all();

        return response()->json([
                    'html' => view('admin.configuracoes.intervdesignacao.designacao_create', $dado)->render()
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
                    'designacao' => 'required',
                    'areaprocessual' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        
        $intervdesignacao = $this->intervdesignacao->create(['designacao' => addslashes($request->designacao)]);

        $intervdesignacao->areasprocessuais()->sync($request->areaprocessual);

        if ($intervdesignacao) {
            
            return response()->json([
                        'success' => true,
                        'message' => 'Designação registada.',
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
        $dados['intervdesignacao'] = $this->intervdesignacao->findorfail($id);
        
        $dados['areasprocessuais'] = $this->areaprocessual->all();
        
        return response()->json([
                    'html' => view('admin.configuracoes.intervdesignacao.designacao_edit', $dados)->render()
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
                    'designacao' => 'required',
                    'areaprocessual' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $intervdesignacao = $this->intervdesignacao->findorfail($id);
        
        $inter =  $intervdesignacao->update(['designacao' => addslashes($request->designacao)]);
        
        $intervdesignacao->areasprocessuais()->sync($request->areaprocessual);
        
        return response()->json([
                    'success' => true,
                    'message' => 'Dados actualizados',
                        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $count = 0;
        
        $count += AreaprocessualIntervdesignacao::where('intervdesignacao_id', $id)->count();
        $count += Processo::where('client_position', $id)->count();

        if ($count == 0) {
            
            $row = $this->intervdesignacao->destroy($id);

            return response()->json([
                        'success' => true,
                        'message' => 'Designação eliminada.'
                            ], 200);
        } else {

            return response()->json([
                        'error' => true,
                        'errormessage' => 'Não é possível eliminar esta designacao.'
                            ], 400);
        }
    }
    
    
    public function cashList(Request $request)
    {
        $user = auth()->user();
        $isEdit = $user->can('designacao_edit');
        $isDelete = $user->can('designacao_delete');

        // Listing column to show
        $columns = array(
            0 => 'id',
            1 => 'nome',
            2 => 'activo',
            3 => 'action'
        );


        $totalData = DB::table('intervdesignacao AS d')
                ->select('d.*')
                ->count();

        $totalRec = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');


        $customcollections = DB::table('intervdesignacao AS d')
                ->select('d.*')
                ->when($search, function ($query, $search) {
            return $query->where('d.designacao', 'LIKE', "%{$search}%");
        });

        // dd($totalData);

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
            $row['nome'] = htmlspecialchars($item->designacao);


            if ($isEdit == "1") {
                $row['activo'] = $this->status($item->activo, $item->id, route('interv.designacao.status'));
            } else {
                $row['activo'] = [];
            }

            if ($isEdit == "1" || $isDelete == "1") {
                $row['action'] = $this->action([
                    'edit_modal' => collect([
                        'id' => $item->id,
                        'action' => route('interv-designacao.edit', $item->id),
                        'target' => '#addtag'
                    ]),
                    'edit_permission' => $isEdit,
                    'delete' => collect([
                        'id' => $item->id,
                        'action' => route('interv-designacao.destroy', $item->id),
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
     * 
     * @param Request $request
     * @return type
     */
    public function changeStatus(Request $request)
    {
        // dd($request->all());

        $statuscode = 400;
        
        $data = $this->intervdesignacao->findOrFail($request->id);
        
        $data->activo = $request->status == 'true' ? 'S' : 'N';

        if ($data->save()) {
            $statuscode = 200;
        }
        $status = $request->status == 'Yes' ? 'S' : 'N';
        $message = 'Estado alterado.';

        return response()->json([
                    'success' => true,
                    'message' => $message
                        ], $statuscode);
    }
}
