<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TipoProcesso;
use App\Models\TipoDespesa;
use App\Models\ItemDespesa;
use Validator;
use App\Traits\DatatablTrait;
use Session;
use DB;

// use App\Helpers\LogActivity;

class TipoDespesaController extends Controller {

    use DatatablTrait;

    public function __construct() {
        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        
        if (!$user->can('expense_type_list'))
            return redirect()->back();
        
        return view('admin.despesa.expense_type');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response()->json([
                    'html' => view('admin.despesa.expense_type_create')->render()
        ]);
    }

    public function expenceList(Request $request)
    {

        $user = auth()->user();
        $isEdit = $user->can('expense_type_edit');
        $isDelete = $user->can('expense_type_delete');


        // Listing column to show
        $columns = array(
            0 => 'id',
            1 => 'name',
            2 => 'is_active',
        );

        $totalData = TipoDespesa::count();
        $totalRec = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $customcollections = TipoDespesa::when($search, function ($query, $search) {
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

            $row['name'] = $item->nome;


            if ($isEdit == "1") {
                $row['is_active'] = $this->status($item->activo, $item->id, route('expense.status'));
            } else {
                $row['is_active'] = [];
            }
            if ($isEdit == "1" || $isDelete == "1") {

                $row['action'] = $this->action([
                    'edit_modal' => collect([
                        'id' => $item->id,
                        'action' => route('expense-type.edit', $item->id),
                        'target' => '#addtag'
                    ]),
                    'edit_permission' => $isEdit,
                    'delete_permission' => $isDelete,
                    'delete' => collect([
                        'id' => $item->id,
                        'action' => route('expense-type.destroy', $item->id),
                    ])
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
                    'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $tipodespesa = new TipoDespesa();
        $tipodespesa->nome = addslashes($request->name);
        $tipodespesa->descricao = addslashes($request->description);
        $tipodespesa->save();

        return response()->json([
                    'success' => true,
                    'message' => 'Tipo de despesa registado',
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
    public function edit($id)
    {
        $data['expense'] = TipoDespesa::findOrFail($id);

        return response()->json([
                    'html' => view('admin.despesa.expense_type_edit', $data)->render()
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
                    'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $tipodespesa = TipoDespesa::findOrFail($id);
        $tipodespesa->nome = addslashes($request->name);
        $tipodespesa->descricao = addslashes($request->description);
        $tipodespesa->save();

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
        $data = TipoDespesa::findOrFail($request->id);
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

    public function destroy($id)
    {

        $count = ItemDespesa::where('category_id', $id)->count();
        if ($count == 0) {
            $row = TipoDespesa::destroy($id);

            return response()->json([
                        'success' => true,
                        'message' => 'Expense type deleted successfully.'
                            ], 200);
        } else {

            return response()->json([
                        'error' => true,
                        'errormessage' => 'You cant delete vendor because it is use in other module.'
                            ], 400);
        }
    }

}
