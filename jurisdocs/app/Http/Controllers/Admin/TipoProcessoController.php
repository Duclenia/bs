<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TipoProcesso;
use App\Models\Processo;
use App\Models\AreaProcessual;
use Validator;
use App\Traits\DatatablTrait;
use App\Models\AreaprocessualTipoprocesso;
use Session;
use DB;

// use App\Helpers\LogActivity;

class TipoProcessoController extends Controller {

    use DatatablTrait;
    
    private $areaprocessual;
    private $tipoprocesso;

    public function __construct(AreaProcessual $areaprocessual, TipoProcesso $tipoprocesso)
    {
        $this->areaprocessual = $areaprocessual;
        
        $this->tipoprocesso = $tipoprocesso;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        $user = auth()->user();
        if (!$user->can('case_type_list')) 
            return redirect()->back();
        
        return view('admin.configuracoes.tipo-processo.casetype');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        
        $dado['areasprocessuais'] = $this->areaprocessual->all();
        
        return response()->json([
                    'html' => view('admin.configuracoes.tipo-processo.casetype_create', $dado)->render()
        ]);
    }

    public function cashTypeList(Request $request) {

        $user = auth()->user();
        $isEdit = $user->can('case_type_edit');
        $isDelete = $user->can('case_type_delete');

        // Listing column to show
        $columns = array(
            0 => 'id',
            1 => 'designacao',
            2 => 'activo',
            3 => 'action'
        );


        $totalData = DB::table('tipoprocesso AS t')
                ->select('t.*')
                ->count();

        $totalRec = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');


        $customcollections = DB::table('tipoprocesso AS t')
                ->select('t.*')
                ->when($search, function ($query, $search) {
            return $query->where('t.designacao', 'LIKE', "%{$search}%");
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

                $row['designacao'] = $item->designacao;


            if ($isEdit == "1") {

                $row['activo'] = $this->status($item->activo, $item->id, route('cash.type.casetype.status'));
            } else {
                $row['activo'] = [];
            }

            if ($isEdit == "1" || $isDelete == "1") {

                $row['action'] = $this->action([
                    'edit_modal' => collect([
                        'id' => $item->id,
                        'action' => route('case-type.edit', $item->id),
                        'target' => '#addtag'
                    ]),
                    'edit_permission' => $isEdit,
                    'delete' => collect([
                        'id' => $item->id,
                        'action' => route('case-type.destroy', $item->id)
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
                    'case_type_name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $tipoprocesso = $this->tipoprocesso->create(['designacao' => htmlspecialchars($request->case_type_name)]);

        $tipoprocesso->areasprocessuais()->sync($request->areaprocessual);
        
        return response()->json([
                    'success' => true,
                    'message' => 'Tipo de processo registado',
                        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        
        $dados['tipoprocesso'] = TipoProcesso::findorfail($id);
        
        $dados['areasprocessuais'] = $this->areaprocessual->all();

        return response()->json([
                    'html' => view('admin.configuracoes.tipo-processo.casetype_edit', $dados)->render()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
                    'case_type_name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $tipoprocesso = $this->tipoprocesso->findorfail($id);

        $tipoprocesso->update(['designacao' => htmlspecialchars($request->case_type_name) ]);
        
        $tipoprocesso->areasprocessuais()->sync($request->areaprocessual);
        
        return response()->json([
                    'success' => true,
                    'message' => 'Dados actualizado.',
                        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changeStatus(Request $request) {
        // dd($request->all());

        $statuscode = 400;
        $data = TipoProcesso::findOrFail($request->id);
        
        $data->activo = $request->status == 'true' ? 'S' : 'N';

        if ($data->save()) {
            $statuscode = 200;
        }
        $status = $request->status == 'Yes' ? 'Yes' : 'No';
        $message = 'Estado alterado.';

        return response()->json([
                    'success' => true,
                    'message' => $message
                        ], $statuscode);
    }

    public function destroy($id) {
        $count = 0;
        $count += AreaprocessualTipoprocesso::where('tipoprocesso_id', $id)->count();
        $count += Processo::where('tipoprocesso_id', $id)->count();
        
        if ($count == 0) {
            $row = TipoProcesso::destroy($id);

            return response()->json([
                        'success' => true,
                        'message' => 'Tipo de processo eliminado.'
                            ], 200);
        } else {

            return response()->json([
                        'error' => true,
                        'errormessage' => 'Não é possível eliminar este tipo de processo.'
                            ], 400);
        }
    }

}
