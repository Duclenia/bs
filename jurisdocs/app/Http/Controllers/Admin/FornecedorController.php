<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVendor;
use App\Models\Fornecedor;
use App\Traits\DatatablTrait;
use App\Models\Provincia;
use App\Models\Despesa;
use App\Models\Endereco;
use DB;
use Session;
use Validator;

class FornecedorController extends Controller {

    use DatatablTrait;
    
    public function __construct() {
        
        $this->middleware('check.subscricao', ['only' => ['create', 'store']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        if (!$user->can('vendor_list'))
            return redirect()->back();

        return view('admin.fornecedor.vendor');
    }

    public function VendorList(Request $request)
    {
        /*
          |----------------
          | Listing colomns
          |----------------
         */

        $user = auth()->user();
        $isEdit = $user->can('vendor_edit');
        $isDelete = $user->can('vendor_delete');

        $columns = array(
            0 => 'id',
            1 => 'first_name',
            2 => 'mobile',
            3 => 'nif',
            4 => 'is_active',
            5 => 'action',
        );

        // $advocate_id = $this->getLoginUserId();
        $totalData = Fornecedor::count();

        $totalFiltered = $totalData;
        $totalRec = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        /*
          |--------------------------------------------
          | For table search filterfrom frontend site.
          |--------------------------------------------
         */
        $search = $request->input('search.value');


        $customcollections = Fornecedor::when($search, function ($query, $search) {
                    return $query->where('nome', 'LIKE', "%{$search}%")
                                    ->orWhere('sobrenome', 'LIKE', "%{$search}%")
                                    ->orWhere('telefone', 'LIKE', "%{$search}%");
                });

        $totalFiltered = $customcollections->count();

        $customcollections = $customcollections->offset($start)->limit($limit)->orderBy($order, $dir)->get();

        $data = [];

        foreach ($customcollections as $key => $item) {

            $show = route('fornecedor.show', $item->id);

            // $row['id'] = $item->id;
            if (empty($request->input('search.value'))) {
                $final = $totalRec - $start;
                $row['id'] = $final;
                $totalRec--;
            } else {
                $start++;
                $row['id'] = $start;
            }

            $nm = $item->company_name ?? $item->full_name;
            $row['first_name'] = '<a class="title text-primary" href="' . $show . '">' . $nm . '</a>';
            $row['mobile'] = htmlspecialchars($item->telefone);

            $row['nif'] = htmlspecialchars($item->nif);

            if ($isEdit == "1") {
                $row['is_active'] = $this->status($item->activo, $item->id, route('vendor.status'));
            } else {
                $row['is_active'] = [];
            }

            if ($isEdit == "1" || $isDelete == "1") {
                $row['action'] = $this->action([
                    'view' => route('fornecedor.show', $item->id),
                    'edit' => route('fornecedor.edit', $item->id),
                    'delete' => collect([
                        'id' => $item->id,
                        'action' => route('fornecedor.destroy', $item->id),
                    ]),
                    'delete_permission' => $isDelete,
                    'edit_permission' => $isEdit,
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
            "data" => $data
        );

        echo json_encode($json_data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $user = auth()->user();

        if (!$user->can('vendor_add'))
            return redirect()->back();

        $dados['provincias'] = Provincia::where('pais_id', 6)->get();

        return view('admin.fornecedor.vendor_create', $dados);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreVendor $request) {

        $endereco = new Endereco();

        $endereco->numero = addslashes($request->numero);
        $endereco->rua = addslashes($request->rua);
        $endereco->bairro_id = $request->bairro_id;
        $endereco->municipio_id = $request->municipio_id;

        $endereco->save();

        if ($endereco->save()) {

            $fornecedor = new Fornecedor;

            $fornecedor->nome = ($request->tipo_fornecedor == 'P') ? addslashes($request->f_name) : null;
            $fornecedor->sobrenome = ($request->tipo_fornecedor == 'P') ? addslashes($request->l_name) : null;
            $fornecedor->tipo = $request->tipo_fornecedor;
            $fornecedor->company_name = ($request->tipo_fornecedor == 'F') ? addslashes($request->company_name) : null;
            $fornecedor->email = addslashes($request->email);
            $fornecedor->telefone = $request->mobile;
            $fornecedor->nif = addslashes($request->nif);
            $fornecedor->alternate_no = $request->alternate_no;

            $fornecedor->endereco_id = $endereco->id;

            $fornecedor->save();

            if ($fornecedor != null)
                return redirect()->route('fornecedor.index')->with('success', "Fornecedor registado.");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {

        $data['client'] = Fornecedor::findOrfail($id);

        // $data['country'] =$this->getCountryName($data['client']->country_id);
        //  $data['state'] =$this->getStateName($data['client']->state_id);
        //  $data['city'] =$this->getCityName($data['client']->city_id);

        $clientName = Fornecedor::findorfail($id);
        $data['name'] = ($data['client']->tipo == 'P') ? $data['client']->nome . ' ' . $data['client']->nome_meio . ' ' . $data['client']->sobrenome : $data['client']->company_name;

        return view('admin.fornecedor.vendor_view', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = auth()->user();
        if (!$user->can('vendor_edit'))
            return redirect()->back();

        $data['fornecedor'] = Fornecedor::with('endereco')->findOrFail($id);
        $data['provincias'] = Provincia::where('pais_id', 6)->get();

        return view('admin.fornecedor.vendor_edit', $data);
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

        $fornecedor = Fornecedor::findOrFail($id);
        
        $endereco = Endereco::where('id', $fornecedor->endereco_id)->first();

        $input = $request->all();

        $regras = ['mobile' => 'required',
                   'provincia' => 'required|numeric',
                   'municipio_id' => 'required|numeric',
                   'nif' => 'required'
        ];

        if ($fornecedor->nif != $request->nif) {
            $regras = ['nif' => 'unique:fornecedor'];
        }

        if (!empty($fornecedor->email) && $request->email != '') {

            if ($fornecedor->email != $request->email)
                $regras = ['email' => 'unique:fornecedor'];
        }

        $validatedData = Validator::make($input, $regras);

        if ($validatedData->passes())
        {

            $endereco->numero = addslashes($request->numero);
            $endereco->rua = addslashes($request->rua);
            $endereco->bairro_id = $request->bairro_id;
            $endereco->municipio_id = $request->municipio_id;

            $endereco->save();
            
            $fornecedor->nome = ($request->tipo_fornecedor == 'P') ? addslashes($request->f_name) : null;
            $fornecedor->sobrenome = ($request->tipo_fornecedor == 'P') ? addslashes($request->l_name) : null;
            $fornecedor->tipo = $request->tipo_fornecedor;
            $fornecedor->company_name = ($request->tipo_fornecedor == 'F') ? addslashes($request->company_name) : null;
            $fornecedor->email = addslashes($request->email);
            $fornecedor->telefone = $request->mobile;
            $fornecedor->nif = addslashes($request->nif);
            $fornecedor->alternate_no = $request->alternate_no;

            $fornecedor->save();

            return redirect()->route('fornecedor.index')->with('success', "Dados actualizados.");
            
        }

        return back()->with('errors', $validatedData->errors());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        $expenseCount = Despesa::where('vendor_id', $id)->count();
        
        if ($expenseCount == 0) {

            $fornecedor = Fornecedor::find($id);

            $fornecedor->delete();

            //Session::flash('success',"Vendor deleted successfully.");
            return response()->json([
                        'success' => true,
                        'message' => 'Fornecedor eliminado.'
                            ], 200);
        } else {
            
            $statuscode = 400;
            return response()->json([
                        'error' => true,
                        'errormessage' => 'You can not delete vendor because it is use in other module.'
                            ], $statuscode);
        }
        //return redirect()->route('vendor.index');
    }
    
    /**
     * 
     * @param Request $request
     * @return type
     */
    public function changeStatus(Request $request)
    {

        $statuscode = 400;
        $fornecedor = Fornecedor::findOrFail($request->id);

        $fornecedor->activo = $request->status == 'true' ? 'S' : 'N';

        if ($fornecedor->save()) {
            $statuscode = 200;
        }
        $status = $request->status == 'true' ? 'activo' : 'desactivo';
        $message = 'Estado do fornecedor ' . $status;

        return response()->json([
                    'success' => true,
                    'message' => $message
                        ], $statuscode);
    }

    public function check_client_email_exits(Request $request)
    {
        
        if ($request->id == "") {
            $count = Fornecedor::where('email', $request->email)->count();
            if ($count == 0) {
                return 'true';
            } else {
                return 'false';
            }
        } else {
            $count = Fornecedor::where('email', $request->email)
                    ->where('id', '<>', $request->id)
                    ->count();

            return ($count == 0) ? 'true' : 'false';
        }
    }
}
