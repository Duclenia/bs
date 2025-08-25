<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Imposto;
use App\Models\Factura;
use App\Models\Despesa;
use Validator;
use App\Traits\DatatablTrait;
use Session;
use DB;

// use App\Helpers\LogActivity;

class ImpostoController extends Controller
{

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
        if (!$user->can('tax_list')) {
            abort(403, 'Unauthorized action.');
        }

        return view('admin.configuracoes.imposto.tax');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response()->json([
                    'html' => view('admin.configuracoes.imposto.tax_create')->render()
        ]);
    }

    public function taxList(Request $request)
    {

        $user = auth()->user();
        $isEdit = $user->can('tax_edit');
        $isDelete = $user->can('tax_delete');

        // Listing column to show
        $columns = array(
            0 => 'id',
            1 => 'name',
            2 => 'per',
        );


        $totalData = Imposto::count();
        $totalRec = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');


        $customcollections = Imposto::when($search, function ($query, $search) {
                    return $query->where('nome', 'LIKE', "%{$search}%")
                                    ->Orwhere('per', 'LIKE', "%{$search}%");
                });

        // dd($totalData);

        $totalFiltered = $customcollections->count();

        $customcollections = $customcollections->offset($start)->limit($limit)->orderBy($order, $dir)->get();

        $data = [];

        foreach ($customcollections as $key => $item)
        {

            if (empty($request->input('search.value')))
            {
                $final = $totalRec - $start;
                $row['id'] = $final;
                $totalRec--;
            } else {
                $start++;
                $row['id'] = $start;
            }

            // $row['id'] = $item->id;

            $row['name'] = $item->nome;
            if ($item->name == "GST") {
                $row['cgst'] = ($item->per / 2) . " %";
                $row['igst'] = ($item->per / 2) . " %";
                ;
            } else {
                $row['cgst'] = "";
                $row['igst'] = "";
            }

            $row['per'] = $item->per . " %";

            if ($isEdit) {
                $row['is_active'] = $this->status($item->activo, $item->id, route('tax.status'));
            } else {
                $row['is_active'] = [];
            }

            if ($isEdit == "1" || $isDelete == "1")
            {
                $row['action'] = $this->action([
                    'edit_modal' => collect([
                        'id' => $item->id,
                        'action' => route('tax.edit', $item->id),
                        'target' => '#addtag'
                    ]),
                    'edit_permission' => $isEdit,
                    'delete' => collect([
                        'id' => $item->id,
                        'action' => route('tax.destroy', $item->id),
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
                    'name' => 'required',
                    'per' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }


        $imposto = new Imposto();

        $imposto->nome = $request->name;
        $imposto->per = $request->per;
        $imposto->note = $request->note;
        $imposto->save();

        return response()->json([
                    'success' => true,
                    'message' => 'Imposto adicionado',
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

        $data['tax'] = Imposto::findorfail($id);
        return response()->json([
                    'html' => view('admin.configuracoes.imposto.tax_edit', $data)->render()
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
                    'per' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $tax = Imposto::find($id);
        $tax->nome = $request->name;
        $tax->per = $request->per;
        $tax->note = $request->note;
        $tax->save();


        return response()->json([
                    'success' => true,
                    'message' => 'Tax updated successfully',
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
        $data = Imposto::findOrFail($request->id);
        $data->activo = $request->status == 'true' ? 'S' : 'N';

        if ($data->save()) {
            $statuscode = 200;
        }
        $status = $request->status == 'Yes' ? 'Yes' : 'No';
        $message = 'Tax Estado alterado com sucesso.';

        return response()->json([
                    'success' => true,
                    'message' => $message
                        ], $statuscode);
    }

    public function destroy($id) {
        $count = 0;
        $count += Factura::where('tax_id', $id)->count();
        $count += Despesa::where('tax_id', $id)->count();

        if ($count == 0) {
            $row = Imposto::destroy($id);

            return response()->json([
                        'success' => true,
                        'message' => __('Tax deleted successfully.')
                            ], 200);
        } else {

            return response()->json([
                        'error' => true,
                        'errormessage' => __('You cant delete Tax because it is use in other module.')
                            ], 400);
        }
    }

}
