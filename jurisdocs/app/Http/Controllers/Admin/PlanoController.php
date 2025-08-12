<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Traits\DatatablTrait;
use App\Http\Controllers\Controller;
use Validator;
use App\Models\Plano;

class PlanoController extends Controller
{
    use DatatablTrait;
    
    private $plano;
    
    public function __construct(Plano $plano)
    {
        $this->plano = $plano;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.plano.index');
    }
    
    public function planoList(Request $request)
    {
        $user = auth()->user();

        $isEdit = $user->can('editar_plano');
        $isDelete = $user->can('plano_delete');

        // Listing column to show
        $columns = array(
            0 => 'id',
            1 => 'plano',
            2 => 'valor_mensal',
            3 => 'total_processo',
            4 =>  'utilizadores',
            5 => 'action'
        );


        $totalData = Plano::count();
        $totalRec = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $customcollections = Plano::when($search, function ($query, $search) {
                    return $query->where('nome', 'LIKE', "%{$search}%");
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

            $row['plano'] = htmlspecialchars($item->nome);
            $row['valor_mensal'] = $item->valor_mensal;
            $row['total_processo'] = $item->total_processo;
            $row['utilizadores'] = $item->utilizadores;

            if ($isEdit == "1" || $isDelete == "1") {

                $row['action'] = $this->action([
                    'edit_modal' => collect([
                        'id' => $item->id,
                        'action' => route('plano.edit', $item->id),
                        'target' => '#addtag'
                    ]),
                    'edit_permission' => $isEdit,
                    'delete' => collect([
                        'id' => $item->id,
                        'action' => route('plano.destroy', $item->id),
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
        return response()->json([
                    'html' => view('admin.plano.create')->render()
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
                    'valor_mensal' => 'required',
                    'total_processo' => 'required',
                    'total_utilizador' => 'required'
        ]);

        if ($validator->fails())
            return response()->json(['errors' => $validator->errors()->all()]);
        
        
        $plano = $this->plano->create(['nome' => addslashes($request->plano),
                                       'valor_mensal' => $request->valor_mensal,
                                       'total_processo' => $request->total_processo,
                                       'utilizadores' => $request->total_utilizador
                                      ]);
        if ($plano) {
            
            return response()->json([
                        'success' => true,
                        'message' => 'Plano registado',
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
        $plano = $this->plano->findOrFail($id);
        
        return response()->json([
                    'html' => view('admin.plano.edit', compact('plano'))->render()
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
                    'valor_mensal' => 'required',
                    'total_processo' => 'required',
                    'total_utilizador' => 'required'
        ]);

        if ($validator->fails())
            return response()->json(['errors' => $validator->errors()->all()]);
        
        $plano = $this->plano->findOrFail($id);
        
        $plano->nome = addslashes($request->plano);
        $plano->valor_mensal = $request->valor_mensal;
        $plano->total_processo = $request->total_processo;
        $plano->utilizadores = $request->total_utilizador;
        $plano->save();
            
        if($plano->save()){
            
            return response()->json([
                    'error' => true,
                    'message' => 'Plano actualizado',
                        ], 200);
        }else{
            
            return response()->json([
                    'success' => true,
                    'message' => 'Plano não actualizado',
                        ], 422);
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
    
    public function planoCheckExist(Request $request)
    {
        if ($request->id == "") {
            $count = $this->plano->where('nome', $request->plano)->count();
            if ($count == 0) {
                return 'true';
            } else {
                return 'false';
            }
        } else {
            $count = $this->plano->where('nome', '=', $request->plano)
                ->where('id', '<>', $request->id)
                ->count();
            if ($count == 0) {
                return 'true';
            } else {
                return 'false';
            }
        }
    }
}
