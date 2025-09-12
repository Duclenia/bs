<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Gate;
use App\Http\Requests\{StoreClient, UpdateClientRequest};
use App\Http\Controllers\Controller;
use App\Models\{
    Cliente,
    Processo,
    ClienteParte,
    Pais,
    Provincia,
    TipoDocumento,
    Documento,
    TipoPessoa
};
use App\Traits\DatatablTrait;
use App\Traits\Mensagem;
use App\Helpers\LogActivity;
use Illuminate\Support\Facades\Mail;
use App\Mail\{DadosAcesso, SendMailClient};
use App\User;
use DB;

class ClienteController extends Controller
{

    use DatatablTrait;
    use Mensagem;

    private $documento;
    private $cliente;

    public function __construct(Documento $documento, Cliente $cliente)
    {
        $this->documento = $documento;
        $this->cliente = $cliente;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Gate::denies('client_list'))
            return back();

        return view('admin.cliente.client');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Gate::denies('client_add'))
            return back();

        $dados['country'] = Pais::all();
        $dados['state'] = Provincia::all();
        $dados['tipospessoas'] = TipoPessoa::all();
        $dados['tiposdocumentos'] = TipoDocumento::all();

        return view('admin.cliente.client_create', $dados);
    }

    public function ClientList(Request $request)
    {
        $user = auth()->user();
        $isEdit = $user->can('client_edit');
        $isDelete = $user->can('client_delete');

        $columns = array(
            0 => 'id',
            1 => 'nome',
            2 => 'no_registo',
            3 => 'telefone',
            4 => 'processo',
            5 => 'activo'
        );

        $totalData = Cliente::count(); // datata table count

        $totalRec = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $customcollections = Cliente::when($search, function ($query, $search) {
            return $query->where('nome', 'LIKE', "%{$search}%")
                ->orWhere('sobrenome', 'LIKE', "%{$search}%")
                ->orWhere('instituicao', 'LIKE', "%{$search}%");
        });

        $totalFiltered = $customcollections->count();
        $customcollections = $customcollections->offset($start)->limit($limit)->orderBy($order, $dir)->get();
        $data = [];

        foreach ($customcollections as $key => $item) {

            $show = route('clients.show', encrypt($item->id));
            $case_list = url('admin/case-list/' . encrypt($item->id));

            if (empty($request->input('search.value'))) {
                $final = $totalRec - $start;
                $row['id'] = $final;
                $totalRec--;
            } else {
                $start++;
                $row['id'] = $start;
            }

            if (!is_null($item->codigo_verificacao)) {
                $validar = 1;
                $telefone = htmlspecialchars($item->telefone) . ' <font color="#FF0000" size="5px">*</font>';
            } else {

                $validar = 0;
                $telefone = htmlspecialchars($item->telefone);
            }

            $row['primeiro_nome'] = '<a class="title text-primary" href="' . $show . '">' . htmlspecialchars(mb_strtoupper($item->full_name)) . '</a>';
            $row['no_registo'] = str_pad($item->id, 5, '0', STR_PAD_LEFT);
            $row['telefone'] = $telefone;
            $row['processo'] = "<a class='title text-primary' href='{$case_list}'>" . $this->getClientCasesTotal($item->id) . "</a>";

            if ($isEdit) {
                $row['activo'] = $this->status($item->activo, $item->id, route('clients.status'));
            } else {
                $row['activo'] = [];
            }
            $row['action'] = $this->action([
                'view' => route('clients.show', encrypt($item->id)),
                'edit' => route('clients.edit', encrypt($item->id)),
                'edit_permission' => $isEdit,
                'validar_telemovel' => collect([
                    'id' => $item->id,
                    'action' => route('validar.telemovel', $item->id),
                    'target' => '#addtag'
                ]),
                'validar' => $validar,
                'delete' => collect([
                    'id' => $item->id,
                    'action' => route('clients.destroy', encrypt($item->id)),
                ]),
                'delete_permission' => $isDelete,
            ]);

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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreClient $request)
    {

        if (Gate::denies('client_add'))
            return back();

        $cliente = new Cliente;

        $documento = new Documento();

        $user = new User();

        $codigo_verificacao = rand(1111, 9999);

        //Inicia o Database Transaction
        DB::beginTransaction();

        if (isset($request->documento) && isset($request->ndi)) {
            if (!empty($request->documento) && !empty($request->ndi)) {
                $documento->ndi = addslashes($request->ndi);
                $documento->tipo = $request->documento;
                $documento->data_validade = date('Y-m-d', strtotime(LogActivity::commonDateFromat($request->ddvdoc)));

                $documento->save();

                if ($documento->save())
                    $cliente->documento_id = $documento->id;
            }
        }

        if (!empty($request->email)) {
            $password = gerarPalavraPasse();

            $user->email = addslashes($request->email);
            $user->user_type = 'Cliente';
            $user->password = bcrypt($password);
            $user->save();

            if ($user->save()) {
                $cliente->user_id = $user->id;

                $nomeCliente = $request->tipo_cliente == 2 ? ucfirst($request->f_name . ' ' . $request->l_name) : ucfirst($request->instituicao);

                Mail::to($request->email)->queue(new DadosAcesso($nomeCliente, $user->email, $password));
            }
        }

        $cliente->tipo = $request->tipo_cliente;
        $cliente->instituicao = ($request->tipo_cliente != 2) ? addslashes($request->instituicao) : null;
        $cliente->nif = addslashes($request->nif);

        if ($request->tipo_cliente == 2) {
            $cliente->nome = addslashes($request->f_name);
            $cliente->sobrenome = addslashes($request->l_name);
            $cliente->estado_civil = $request->estado_civil;
            $cliente->regime_casamento = $request->regime_casamento;
        }

        $cliente->telefone = $request->mobile;
        $cliente->alternate_no = $request->alternate_no;
        $cliente->endereco = addslashes($request->address);
        $cliente->pais_id = $request->country;
        $cliente->provincia_id = $request->state;
        $cliente->municipio_id = $request->city_id;
        $cliente->client_type = $request->type;
        $cliente->codigo_verificacao = (!empty($request->mobile)) ? $codigo_verificacao : null;
        $cliente->save();

        if ($cliente->save()) {
            $clientId = $cliente->id;

            //Sucesso!
            DB::commit();
        } else {
            //Fail, desfaz as alterações no banco de dados
            DB::rollBack();
        }


        if ($request->type == "single") {
            if (isset($request['group-a']) && count($request['group-a']) > 0) {
                foreach ($request['group-a'] as $key => $value) {
                    if (!empty($value['firstname']) && !empty($value['middlename']) && !empty($value['lastname']) && !empty($value['mobile_client']) && !empty($value['address_client'])) {
                        $ClientPartiesInvoive = new ClienteParte();
                        $ClientPartiesInvoive->cliente_id = $clientId;
                        $ClientPartiesInvoive->party_firstname = $value['firstname'];
                        $ClientPartiesInvoive->party_middlename = $value['middlename'];
                        $ClientPartiesInvoive->party_lastname = $value['lastname'];
                        $ClientPartiesInvoive->party_mobile = $value['mobile_client'];
                        $ClientPartiesInvoive->party_address = $value['address_client'];
                        $ClientPartiesInvoive->save();
                    }
                }
            }
        } else if ($request->type == "multiple") {
            if (isset($request['group-b']) && count($request['group-b']) > 0) {
                foreach ($request['group-b'] as $key => $value) {
                    if (!empty($value['firstname']) && !empty($value['middlename']) && !empty($value['lastname']) && !empty($value['mobile_client']) && !empty($value['address_client']) && !empty($value['advocate_name'])) {
                        $ClientPartiesInvoive = new ClienteParte();
                        $ClientPartiesInvoive->cliente_id = $clientId;
                        $ClientPartiesInvoive->party_firstname = $value['firstname'];
                        $ClientPartiesInvoive->party_middlename = $value['middlename'];
                        $ClientPartiesInvoive->party_lastname = $value['lastname'];
                        $ClientPartiesInvoive->party_mobile = $value['mobile_client'];
                        $ClientPartiesInvoive->party_address = $value['address_client'];
                        $ClientPartiesInvoive->party_advocate = $value['advocate_name'];
                        $ClientPartiesInvoive->save();
                    }
                }
            }
        }

        if ($cliente->telefone)
            $this->enviarSMS($cliente->telefone, $codigo_verificacao);

        return redirect()->route('clients.index')->with('success', __('Client added successfully.'));
    }

    public function storeCliente(Request $request)
    {

        if (Gate::denies('client_add'))
            return back();

        $cliente = new Cliente;
        $documento = new Documento();
        $user = new User();

        $codigo_verificacao = rand(1111, 9999);

        //Inicia o Database Transaction
        DB::beginTransaction();

        if (isset($request->documento) && isset($request->ndi)) {
            if (!empty($request->documento) && !empty($request->ndi)) {
                $documento->ndi = addslashes($request->ndi);
                $documento->tipo = $request->documento;
                $documento->data_validade = date('Y-m-d', strtotime(LogActivity::commonDateFromat($request->ddvdoc)));
                $documento->save();

                if ($documento->save())
                    $cliente->documento_id = $documento->id;
            }
        }

        if (!empty($request->email)) {
            $password = gerarPalavraPasse();

            $user->email = addslashes($request->email);
            $user->user_type = 'Cliente';
            $user->password = bcrypt($password);
            $user->save();

            if ($user->save()) {
                $cliente->user_id = $user->id;

                $nomeCliente = $request->new_client == 2 ? ucfirst($request->f_name . ' ' . $request->l_name) : ucfirst($request->instituicao);

                  Mail::to($request->email)->queue(new DadosAcesso($nomeCliente, $user->email, $password));
            }
        }

        $cliente->tipo = $request->new_client;
        $cliente->instituicao = ($request->new_client != 2) ? addslashes($request->instituicao) : null;
        $cliente->nif = addslashes($request->nif);

        if ($request->new_client == 2) {

            $cliente->nome = addslashes($request->f_name);
            $cliente->sobrenome = addslashes($request->l_name);
            $cliente->estado_civil = $request->estado_civil;
            $cliente->regime_casamento = $request->regime_casamento;
        }else{
             $cliente->nome = $request->instituicao;
        }

        $cliente->telefone = $request->mobile;

        $cliente->alternate_no = $request->alternate_no;
        $cliente->endereco = addslashes($request->address);
        $cliente->pais_id = $request->country;
        $cliente->provincia_id = $request->state;
        $cliente->municipio_id = $request->city_id;
        $cliente->client_type = "single";
        $cliente->codigo_verificacao = (!empty($request->mobile)) ? $codigo_verificacao : null;
        $cliente->save();

        if ($cliente->save()) {
            $clientId = $cliente->id;

            //Sucesso!
            DB::commit();
        } else {
            //Fail, desfaz as alterações no banco de dados
            DB::rollBack();
        }


        if ($request->type == "single") {
            if (isset($request['group-a']) && count($request['group-a']) > 0) {
                foreach ($request['group-a'] as $key => $value) {
                    if (!empty($value['firstname']) && !empty($value['middlename']) && !empty($value['lastname']) && !empty($value['mobile_client']) && !empty($value['address_client'])) {
                        $ClientPartiesInvoive = new ClienteParte();
                        $ClientPartiesInvoive->cliente_id = $clientId;
                        $ClientPartiesInvoive->party_firstname = $value['firstname'];
                        $ClientPartiesInvoive->party_middlename = $value['middlename'];
                        $ClientPartiesInvoive->party_lastname = $value['lastname'];
                        $ClientPartiesInvoive->party_mobile = $value['mobile_client'];
                        $ClientPartiesInvoive->party_address = $value['address_client'];
                        $ClientPartiesInvoive->save();
                    }
                }
            }
        } else if ($request->type == "multiple") {
            if (isset($request['group-b']) && count($request['group-b']) > 0) {
                foreach ($request['group-b'] as $key => $value) {
                    if (!empty($value['firstname']) && !empty($value['middlename']) && !empty($value['lastname']) && !empty($value['mobile_client']) && !empty($value['address_client']) && !empty($value['advocate_name'])) {
                        $ClientPartiesInvoive = new ClienteParte();
                        $ClientPartiesInvoive->cliente_id = $clientId;
                        $ClientPartiesInvoive->party_firstname = $value['firstname'];
                        $ClientPartiesInvoive->party_middlename = $value['middlename'];
                        $ClientPartiesInvoive->party_lastname = $value['lastname'];
                        $ClientPartiesInvoive->party_mobile = $value['mobile_client'];
                        $ClientPartiesInvoive->party_address = $value['address_client'];
                        $ClientPartiesInvoive->party_advocate = $value['advocate_name'];
                        $ClientPartiesInvoive->save();
                    }
                }
            }
        }

        /* if ($cliente->telefone)
             $this->enviarSMS($cliente->telefone, $codigo_verificacao); */

            return $cliente->id;
    }

    public function show($id)
    {
        $data['single'] = array();
        $data['multiple'] = array();
        $data['client'] = Cliente::findOrFail(decrypt($id));
        $data['single'] = ClienteParte::where('cliente_id', decrypt($id))->get();
        $clientName = Cliente::findOrFail(decrypt($id));
        $data['name'] = $clientName->full_name;

        return view('admin.cliente.view.client_detail', $data);
    }

    public function edit($id)
    {
        $user = auth()->user();
        if (!$user->can('client_edit'))
            return back();

        $dados['client'] = $this->cliente->with('utilizador', 'pais', 'provincia', 'municipio', 'documento')->findOrFail(decrypt($id));

        $dados['country'] = DB::table('pais')->where('id', 6)->first();
        $dados['states'] = DB::table('provincia')->where('pais_id', $dados['client']->pais_id)->get();
        $dados['citys'] = DB::table('municipio')->where('provincia_id', $dados['client']->provincia_id)->get();
        $dados['tipospessoas'] = TipoPessoa::all();
        $dados['tiposdocumentos'] = TipoDocumento::all();

        $dados['client_parties_invoive'] = ClienteParte::where('cliente_id', decrypt($id))->get();
        return view('admin.cliente.client_edit', $dados);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateClientRequest $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateClientRequest $request, $id)
    {

        DB::table('cliente_parte')->where('cliente_id', $id)->delete();

        $cliente = $this->cliente->with('utilizador')->findOrFail($id);


        if (isset($request->documento) && isset($request->ndi)) {
            if (!empty($request->documento) && !empty($request->ndi)) {
                $documento = $this->documento->find($cliente->documento_id);

                $documento->tipo = $request->documento;
                $documento->ndi = addslashes($request->ndi);
                $documento->data_validade = date('Y-m-d', strtotime(LogActivity::commonDateFromat($request->ddvdoc)));
                $documento->save();
            }
        }

        if (!empty($request->email)) {
            $password = gerarPalavraPasse();

            $nomeCliente = $request->tipo_cliente == 2 ? ucfirst($request->f_name . ' ' . $request->l_name) : ucfirst($request->instituicao);

            if ($cliente->utilizador) {
                if ($cliente->utilizador->email != $request->email) {
                    $user = $cliente->utilizador;
                    $user->email = addslashes($request->email);
                    $user->password = bcrypt($password);
                    $user->save();

                    Mail::to($request->email)->queue(new SendMailClient($nomeCliente, $user->email, $password));
                }
            } else {

                $user = new User();

                $user->email = addslashes($request->email);
                $user->user_type = 'Cliente';
                $user->password = bcrypt($password);
                $user->save();

                $cliente->user_id = $user->id;

                Mail::to($request->email)->queue(new DadosAcesso($nomeCliente, $user->email, $password));
            }
        }

        $codigo_verificacao = rand(1111, 9999);

        if (!empty($cliente->telefone) && !empty($request->mobile)) {

            if (strcmp($cliente->telefone, $request->mobile) != 0):

                $cliente->codigo_verificacao = $codigo_verificacao;

                $this->enviarSMS($request->mobile, $codigo_verificacao);

            endif;
        }

        $cliente->tipo = $request->tipo_cliente;
        $cliente->instituicao = ($request->tipo_cliente != 2) ? addslashes($request->instituicao) : null;
        $cliente->nif = addslashes($request->nif);

        if ($request->tipo_cliente == 2) {
            $cliente->nome = addslashes($request->f_name);
            $cliente->sobrenome = addslashes($request->l_name);
            $cliente->estado_civil = $request->estado_civil;
            $cliente->regime_casamento = $request->regime_casamento;
        }

        $cliente->telefone = $request->mobile;
        $cliente->alternate_no = $request->alternate_no;
        $cliente->endereco = addslashes($request->address);
        $cliente->pais_id = $request->country;
        $cliente->provincia_id = $request->state;
        $cliente->municipio_id = $request->city_id;
        $cliente->client_type = $request->type;

        $cliente->save();

        $clientId = $id;

        if ($request->change_court_chk == "Yes") {
            if ($request->type == "single") {
                if (isset($request['group-a']) && count($request['group-a']) > 0) {
                    foreach ($request['group-a'] as $key => $value) {
                        $ClientPartiesInvoive = new ClienteParte();
                        $ClientPartiesInvoive->cliente_id = $clientId;
                        $ClientPartiesInvoive->party_firstname = $value['firstname'];
                        $ClientPartiesInvoive->party_middlename = $value['middlename'];
                        $ClientPartiesInvoive->party_lastname = $value['lastname'];
                        $ClientPartiesInvoive->party_mobile = $value['mobile_client'];
                        $ClientPartiesInvoive->party_address = $value['address_client'];
                        $ClientPartiesInvoive->save();
                    }
                }
            } else if ($request->type == "multiple") {

                if (isset($request['group-b']) && count($request['group-b']) > 0) {
                    foreach ($request['group-b'] as $key => $value) {
                        $ClientPartiesInvoive = new ClienteParte();
                        $ClientPartiesInvoive->cliente_id = $clientId;
                        $ClientPartiesInvoive->party_firstname = $value['firstname'];
                        $ClientPartiesInvoive->party_middlename = $value['middlename'];
                        $ClientPartiesInvoive->party_lastname = $value['lastname'];
                        $ClientPartiesInvoive->party_mobile = $value['mobile_client'];
                        $ClientPartiesInvoive->party_address = $value['address_client'];
                        $ClientPartiesInvoive->party_advocate = $value['advocate_name'];
                        $ClientPartiesInvoive->save();
                    }
                }
            }
        }
        return redirect()->route('clients.index')->with('success', __('Client Update successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $appointments = DB::table('agenda')->where('cliente_id', decrypt($id))->count();
        $processos = DB::table('processo')->where('cliente_id', decrypt($id))->count();

        if ($appointments > 0 || $processos > 0) {
            session()->flash('error', "Não é possível eliminar este cliente.");
        }

        $cliente = $this->cliente->findOrFail(decrypt($id));

        $cliente->delete();

        ClienteParte::where('cliente_id', decrypt($id))->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cliente eliminado.'
        ], 200);
    }

    public function changeStatus(Request $request)
    {
        $statuscode = 400;
        $cliente = $this->cliente->findOrFail($request->id);

        $cliente->activo = $request->status == 'true' ? 'S' : 'N';

        if ($cliente->save()) {
            $statuscode = 200;
        }
        $status = ($request->status == 'true') ? 'activo' : 'desactivo';
        $message = 'Cliente estado ' . $status . ' successfully.';

        return response()->json([
            'success' => true,
            'message' => $message
        ], $statuscode);
    }

    public function check_client_email_exits(Request $request)
    {
        $count = 0;

        if (empty($request->id)) {
            $count = User::where('email', $request->email)->count();
        } else {
            $count = User::where('email', '=', $request->email)
                ->where('id', '<>', $request->id)
                ->count();
        }

        return response()->json([
            'exists' => $count > 0
        ]);
    }

    public function caseDetail($id)
    {
        $user = auth()->user();
        if (!$user->can('case_list'))
            return back();

        $totalCourtCase = Processo::where('cliente_id', decrypt($id))->count();
        $client = Cliente::findOrFail(decrypt($id));
        $name = $client->full_name;

        return view('admin.cliente.view.cases_view', ['advo_client_id' => decrypt($id), 'name' => $name, 'totalCourtCase' => $totalCourtCase, 'client' => $client]);
    }

    public function accountDetail($id)
    {

        $user = auth()->user();

        if (!$user->can('invoice_list'))
            return back();

        $client = $this->cliente->findOrFail($id);
        $name = $client->full_name;

        return view('admin.cliente.view.client_account', ['advo_client_id' => $id, 'name' => $name, 'client' => $client]);
    }

    public function enviarSMS($contacto, $codigo)
    {

        //        $tel = explode(' ', $contacto);
        //
        //        $telemovel = $tel[0] . $tel[1] . $tel[2];

        $this->enviarCodigoVerificacao($contacto, $codigo);
    }

    public function getModalValidarTelemovel($id)
    {
        $dados['cliente'] = Cliente::findOrFail($id);

        return response()->json([
            'html' => view('admin.cliente.validar_telemovel', $dados)->render()
        ]);
    }

    public function setCodVerificacao(Request $request)
    {
        $cliente = $this->cliente->findOrFail($request->id);

        if ($cliente) {
            $codigo_verificacao = rand(1111, 9999);

            $cliente->codigo_verificacao = $codigo_verificacao;
            $cliente->save();

            $this->enviarSMS($cliente->telefone, $codigo_verificacao);

            return response()->json([
                'success' => true,
                'message' => 'Código enviado',
            ], 200);
        }
    }

    public function verificarTelemovel(Request $request, $id)
    {

        $cliente = $this->cliente->where('id', $id)
            ->where('codigo_verificacao', $request->codigo_verificacao)
            ->first();

        if ($cliente) {
            $cliente->codigo_verificacao = null;
            $cliente->save();

            return response()->json([
                'success' => true,
                'message' => 'Telemóvel verificado.',
            ], 200);
        } else {

            return response()->json([
                'error' => true,
                'errormessage' => 'O código de verificação está incorrecto.'
            ], 400);
        }
    }
}
