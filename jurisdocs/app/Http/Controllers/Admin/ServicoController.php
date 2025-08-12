<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\DatatablTrait;
use App\Models\Servico;
use Session;
use DB;
use App\Models\ItemFactura;

class ServicoController extends Controller
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
        if (!$user->can('service_list'))
            return redirect()->back();
        
        return view('admin.servico.service');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
        return response()->json([
                    'html' => view('admin.servico.create')->render()
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
        
        $servico = new Servico();
        $servico->nome = addslashes($request->name);
        $servico->valor = $request->amount;
        $servico->save();

        return response()->json([
                    'success' => true,
                    'message' => 'Serviço criado',
                        ], 200);
    }

    public function serviceList(Request $request)
    {

        $user = auth()->user();
        $isEdit = $user->can('service_edit');
        $isDelete = $user->can('service_delete');


        // Listing column to show
        $columns = array(
            0 => 'id',
            1 => 'nome',
            2 => 'valor',
            3 => 'activo',
        );

        $totalData = Servico::count();
        $totalRec = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $customcollections = Servico::when($search, function ($query, $search) {
                    return $query->where('nome', 'LIKE', "%{$search}%");
                });

        $totalFiltered = $customcollections->count();

        $customcollections = $customcollections->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

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

            $row['name'] = htmlspecialchars($item->nome);
            $row['amount'] = htmlspecialchars($item->valor);


            if ($isEdit == "1") {
                $row['is_active'] = $this->status($item->activo, $item->id, route('service.status'));
            } else {
                $row['is_active'] = [];
            }
            if ($isEdit == "1" || $isDelete == "1") {

                $row['action'] = $this->action([
                    'edit_modal' => collect([
                        'id' => $item->id,
                        'action' => route('servico.edit', $item->id),
                        'target' => '#addtag'
                    ]),
                    'edit_permission' => $isEdit,
                    'delete_permission' => $isDelete,
                    'delete' => collect([
                        'id' => $item->id,
                        'action' => route('servico.destroy', $item->id),
                    ]),
                    'edit_permission' => $isEdit,
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->data['service'] = Servico::findOrFail($id);
        return response()->json([
                    'html' => view('admin.servico.edit', $this->data)->render()
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
        
        $servico = Servico::findOrFail($id);
        $servico->nome = addslashes($request->name);
        $servico->valor = $request->amount;
        $servico->save();

        return response()->json([
                    'success' => true,
                    'message' => 'Serviço actualizado',
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
       
        $count = ItemFactura::where('servico_id', $id)->count();

        if ($count == 0) {
            Servico::destroy($id);

            return response()->json([
                        'success' => true,
                        'message' => 'Serviço eliminado.'
                            ], 200);
        } else {

            return response()->json([
                        'error' => true,
                        'errormessage' => 'Não é possível eliminar este serviço.'
                            ], 400);
        }
    }

    public function changeStatus(Request $request)
    {
        
        $statuscode = 400;
        $servico = Servico::findOrFail($request->id);
        
        $servico->activo = $request->status == 'true' ? 'S' : 'N';

        if ($servico->save()) {
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
