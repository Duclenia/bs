<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Bairro;
use App\Traits\DatatablTrait;
use App\Models\Municipio;
use Validator;

class BairroController extends Controller
{
    use DatatablTrait;
    
    private $municipio;
    private $bairro;
    
    
    public function __construct(Municipio $municipio, Bairro $bairro)
    {
        $this->municipio = $municipio;
        $this->bairro = $bairro;

    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();

        if (!$user->can('listar_bairro'))
            return back();

        return view('admin.configuracoes.bairro.bairro');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dado['municipios'] = $this->municipio->all();

        return response()->json([
                    'html' => view('admin.configuracoes.bairro.bairro_create', $dado)->render()
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
                    'bairro' => 'required',
                    'municipio' => 'required'
        ]);

        if ($validator->fails())
            return response()->json(['errors' => $validator->errors()->all()]);
        
        
        $bairro = $this->bairro->create(['nome' => addslashes($request->bairro)]);

        $bairro->municipios()->sync($request->municipio);

        if ($bairro) {
            
            return response()->json([
                        'success' => true,
                        'message' => 'Bairro registado',
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
        $dados['bairro'] = $this->bairro->findOrFail($id);
        
        $dados['municipios'] = $this->municipio->all();
        
        return response()->json([
                    'html' => view('admin.configuracoes.bairro.bairro_edit', $dados)->render()
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
                    'bairro' => 'required',
                    'municipio' => 'required'
        ]);

        if ($validator->fails())
            return response()->json(['errors' => $validator->errors()->all()]);
        

        $bairro = $this->bairro->findorfail($id);
        
        $bairro->update(['nome' => addslashes($request->bairro)]);
        
        $bairro->municipios()->sync($request->municipio);
        
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
        //
    }
    
    public function caseStatusList(Request $request)
    {
        
        $user = auth()->user();

        $isEdit = $user->can('bairro_edit');
        $isDelete = $user->can('bairro_delete');

        // Listing column to show
        $columns = array(
            0 => 'id',
            1 => 'nome',
            2 => 'action'
        );


        $totalData = Bairro::count();
        $totalRec = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $customcollections = Bairro::when($search, function ($query, $search) {
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


            $row['nome'] = htmlspecialchars($item->nome);

            if ($isEdit == "1" || $isDelete == "1") {

                $row['action'] = $this->action([
                    'edit_modal' => collect([
                        'id' => $item->id,
                        'action' => route('bairro.edit', $item->id),
                        'target' => '#addtag'
                    ]),
                    'edit_permission' => $isEdit,
                    'delete' => collect([
                        'id' => $item->id,
                        'action' => route('bairro.destroy', $item->id),
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
    
    
    public function bairro_check_exist(Request $request)
    {

        if ($request->id == "") {
            $count = $this->bairro->where('nome', $request->bairro)->count();
            if ($count == 0) {
                return 'true';
            } else {
                return 'false';
            }
        } else {
            $count = $this->bairro->where('nome', '=', $request->bairro)
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
