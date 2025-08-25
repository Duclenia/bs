<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TipoProcesso;
use App\Models\EstadoProcesso;
use App\Models\Processo;
use Validator;
use App\Traits\DatatablTrait;
use Session;
use App\Models\AreaProcessual;
use DB;

// use App\Helpers\LogActivity;

class EstadoProcessoController extends Controller
{

    use DatatablTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        if (!$user->can('case_status_list'))
            return redirect()->back();

        return view('admin.configuracoes.case-status.case_status');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dados['areasprocessuais'] = AreaProcessual::all();

        return response()->json([
                    'html' => view('admin.configuracoes.case-status.case_status_create', $dados)->render()
        ]);
    }

    public function caseStatusList(Request $request)
    {

        $user = auth()->user();
        $isEdit = $user->can('case_status_edit');
        $isDelete = $user->can('case_status_delete');

        // Listing column to show
        $columns = array(
            0 => 'id',
            1 => 'case_status_name',
            2 => 'is_active',
        );


        $totalData = EstadoProcesso::count();
        $totalRec = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');


        $customcollections = EstadoProcesso::when($search, function ($query, $search) {
                    return $query->where('estado', 'LIKE', "%{$search}%");
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

            $row['case_status_name'] = htmlspecialchars($item->estado);

            if ($isEdit == "1") {

                $row['is_active'] = $this->status($item->activo, $item->id, route('case.status'));
            } else {
                $row['is_active'] = [];
            }


            if ($isEdit == "1" || $isDelete == "1") {
                $row['action'] = $this->action([
                    'edit_modal' => collect([
                        'id' => $item->id,
                        'action' => route('case-status.edit', $item->id),
                        'target' => '#addtag'
                    ]),
                    'edit_permission' => $isEdit,
                    'delete' => collect([
                        'id' => $item->id,
                        'action' => route('case-status.destroy', $item->id),
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
                    'case_status' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $casetype = new EstadoProcesso();

        $casetype->estado = addslashes($request->case_status);
        $casetype->save();

        $casetype->areasprocessuais()->sync($request->areaprocessual);

        return response()->json([
                    'success' => true,
                    'message' => 'Estado do processo registado',
                        ], 200);
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

        $data['CaseStatus'] = EstadoProcesso::findorfail($id);

        $data['areasprocessuais'] = AreaProcessual::all();

        return response()->json([
                    'html' => view('admin.configuracoes.case-status.case_status_edit', $data)->render()
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
                    'case_status' => 'required',
                    'areaprocessual' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $estadoProcesso = EstadoProcesso::findorfail($id);

        $estadoProcesso->estado = $request->case_status;

        $estadoProcesso->save();

        $estadoProcesso->areasprocessuais()->sync($request->areaprocessual);

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
    public function changeStatus(Request $request)
    {

        $statuscode = 400;
        $data = EstadoProcesso::findOrFail($request->id);
        $data->activo = $request->status == 'true' ? 'S' : 'N';

        if ($data->save()) {
            $statuscode = 200;
        }
        $status = $request->status == 'Yes' ? 'Yes' : 'No';
        $message = 'Case Estado alterado com sucesso.';

        return response()->json([
                    'success' => true,
                    'message' => $message
                        ], $statuscode);
    }

    public function destroy($id)
    {

        $count = 0;
        $count += Processo::where('estado', $id)->count();

        if ($count == 0) {
            $row = EstadoProcesso::destroy($id);

            return response()->json([
                        'success' => true,
                        'message' => 'Case Status deleted successfully.'
                            ], 200);
        } else {

            return response()->json([
                        'error' => true,
                        'errormessage' => 'Não é possível eliminar este estado de processo.'
                            ], 400);
        }
    }

}
