<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\DatatablTrait;
use Validator;
use App\Models\OrgaoJudiciario;

class OrgaoJudiciarioController extends Controller
{
    
    use DatatablTrait;
    
    private $orgaoJudiciario;
    
    public function __construct(OrgaoJudiciario $orgaoJudiciario)
    {
        $this->orgaoJudiciario = $orgaoJudiciario;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        if (!$user->can('listar_orgao_judiciario'))
            return redirect()->back();

        return view('admin.configuracoes.orgao_judiciario.orgao_judiciario');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response()->json([
                    'html' => view('admin.configuracoes.orgao_judiciario.orgao_judiciario_create')->render()
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
                    'orgao_judiciario' => 'required'    
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        
        $orgaoJudiciario = $this->orgaoJudiciario->create(['designacao' => addslashes($request->orgao_judiciario)]);

        if ($orgaoJudiciario) {
            
            return response()->json([
                        'success' => true,
                        'message' => 'Órgão judiciário registado',
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
        $orgaoJudiciario = $this->orgaoJudiciario->findOrFail($id);
        
        return response()->json([
                    'html' => view('admin.configuracoes.orgao_judiciario.orgao_judiciario_edit', compact('orgaoJudiciario'))->render()
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
                    'orgao_judiciario' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        
        $orgaoJudiciario = $this->orgaoJudiciario->findOrFail($id);

        $orgaoJudiciario->update(['designacao' => htmlspecialchars($request->orgao_judiciario)]);

        return response()->json([
                    'success' => true,
                    'message' => 'Dado actualizado.',
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
        
    }
    
    public function listarOrgaosJudiciarios(Request $request)
    {
        $user = auth()->user();

        $isEdit = $user->can('editar_orgao_judiciario');
        $isDelete = $user->can('eliminar_orgao_judiciario');

        // Listing column to show
        $columns = array(
            0 => 'id',
            1 => 'designacao',
            2 => 'action'
        );


        $totalData = OrgaoJudiciario::count();
        $totalRec = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $customcollections = OrgaoJudiciario::when($search, function ($query, $search) {
                    return $query->where('designacao', 'LIKE', "%{$search}%");
                });

        $totalFiltered = $customcollections->count();

        $customcollections = $customcollections->offset($start)->limit($limit)->orderBy($order, $dir)->get();

        $data = [];

        foreach ($customcollections as $key => $item) {

            if (empty($request->input('search.value'))) {
                $final = $totalRec - $start;
                $row['id'] = $final;
                $totalRec--;
            } else {
                $start++;
                $row['id'] = $start;
            }

            $row['designacao'] = htmlspecialchars($item->designacao);

            if ($isEdit == "1" || $isDelete == "1") {

                $row['action'] = $this->action([
                    'edit_modal' => collect([
                        'id' => $item->id,
                        'action' => route('orgao-judiciario.edit', $item->id),
                        'target' => '#addtag'
                    ]),
                    'edit_permission' => $isEdit,
                    'delete' => collect([
                        'id' => $item->id,
                        'action' => route('orgao-judiciario.destroy', $item->id),
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
}
