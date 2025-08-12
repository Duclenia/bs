<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ConfiguracaoFactura;
use Validator;
use App\Traits\DatatablTrait;
use Session;
use DB;

// use App\Helpers\LogActivity;

class ConfiguracaoFacturaController extends Controller
{
    use DatatablTrait;

    public function __construct()
    {

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $user = auth()->user();
        if (!$user->can('general_setting_edit')) {
            abort(403, 'Unauthorized action.');
        }
        $data['setting'] = ConfiguracaoFactura::find(1);
        return view('admin.configuracoes.invoice-setting.invoice_edit', $data);
    }

    public function store(Request $request)
    {

        $this->validate($request, [
            'forment' => 'required',
        ]);

        $inv = ConfiguracaoFactura::find(1);
        $inv->prefix = $request->invoice_prefex;
        $inv->invoice_formet = $request->forment;
        $inv->client_note = $request->predefine_client;
        $inv->term_condition = $request->predefine_term_note;
        $inv->save();

        Session::flash('success', "Configuração adicionada.");
        return redirect()->route('invoice-setting.index');
    }

}
