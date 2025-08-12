<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Processo;
use App\Models\Tribunal;
use Validator;
use App\Traits\DatatablTrait;
use App\Models\AreaProcessual;
use App\Models\TribunalAreaprocessual;
use Session;
use DB;

// use App\Helpers\LogActivity;

class TribunalController extends Controller
{

    use DatatablTrait;

    private $areaprocessual;
    private $tribunal;

    public function __construct(AreaProcessual $areaprocessual, Tribunal $tribunal)
    {
        
        $this->areaprocessual = $areaprocessual;
        
        $this->tribunal = $tribunal;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();

        if (!$user->can('court_list'))
            return redirect()->back();

        return view('admin.configuracoes.tribunal.court');
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
                    'html' => view('admin.configuracoes.tribunal.court_create', $dado)->render()
        ]);
    }

    public function cashList(Request $request)
    {

        $user = auth()->user();
        $isEdit = $user->can('court_edit');
        $isDelete = $user->can('court_delete');

        // Listing column to show
        $columns = array(
            0 => 'id',
            1 => 'nome',
            2 => 'activo',
            3 => 'action'
        );


        $totalData = DB::table('tribunal AS t')
                ->select('t.*')
                ->count();

        $totalRec = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');


        $customcollections = DB::table('tribunal AS t')
                ->select('t.*')
                ->when($search, function ($query, $search) {
            return $query->where('t.nome', 'LIKE', "%{$search}%");
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

            if ($isEdit == "1") {
                $row['activo'] = $this->status($item->activo, $item->id, route('court.status'));
            } else {
                $row['activo'] = [];
            }

            if ($isEdit == "1" || $isDelete == "1") {
                $row['action'] = $this->action([
                    'edit_modal' => collect([
                        'id' => $item->id,
                        'action' => route('tribunal.edit', $item->id),
                        'target' => '#addtag'
                    ]),
                    'edit_permission' => $isEdit,
                    'delete' => collect([
                        'id' => $item->id,
                        'action' => route('tribunal.destroy', $item->id),
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
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
                    'court_name' => 'required',
                    'areaprocessual' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        
        $tribunal = $this->tribunal->create(['nome' => addslashes($request->court_name)]);

        $tribunal->areasprocessuais()->sync($request->areaprocessual);

        if ($tribunal) {
            
            return response()->json([
                        'success' => true,
                        'message' => 'Tribunal registado',
                            ], 200);
        }
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
        
        
        $data['tribunal'] = Tribunal::findorfail($id);
        
        $data['areasprocessuais'] = AreaProcessual::all();
        
        return response()->json([
                    'html' => view('admin.configuracoes.tribunal.court_edit', $data)->render()
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
                    'court_name' => 'required',
                    'areaprocessual' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $tribunal = $this->tribunal->findorfail($id);
        
        $tribunal->update(['nome' => addslashes($request->court_name)]);
        
        $tribunal->areasprocessuais()->sync($request->areaprocessual);
        
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
    public function changeStatus(Request $request) {
        // dd($request->all());

        $statuscode = 400;
        $data = Tribunal::findOrFail($request->id);
        $data->activo = $request->status == 'true' ? 'S' : 'N';

        if ($data->save()) {
            $statuscode = 200;
        }
        $status = $request->status == 'Yes' ? 'S' : 'N';
        $message = 'Court status changed successfully.';

        return response()->json([
                    'success' => true,
                    'message' => $message
                        ], $statuscode);
    }

    public function destroy($id)
    {
        $count = 0;
        
        $count += TribunalAreaprocessual::where('tribunal_id', $id)->count();
        $count += Processo::where('tribunal_id', $id)->count();

        if ($count == 0) {
            $row = Tribunal::destroy($id);

            return response()->json([
                        'success' => true,
                        'message' => 'Tribunal eliminado.'
                            ], 200);
        } else {

            return response()->json([
                        'error' => true,
                        'errormessage' => 'Não é possível eliminar este tribunal.'
                            ], 400);
        }
    }

}
