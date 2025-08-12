<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// use App\Model\State;
use Session;
use App\Models\Mailsetup;
use App\Models\sqlBackup;
use App\Models\Admin;
use App\Models\ConfiguracaoGeral;
use App\Models\Provincia;
use App\Models\Endereco;
use DB;
use Storage;
use Camroncade\Timezone\Facades\Timezone;

class ConfiguracaoGeralController extends Controller
{

    public function index()
    {
        $user = auth()->user();
        if (!$user->can('general_setting_edit'))
            return redirect()->back();

        //'Asia/Kolkata'
        $dados['timezone'] = DB::table('fusohorario')->get();
        $GeneralSettings = ConfiguracaoGeral::with('endereco')->findOrfail(1);
        $dados['title'] = 'Mail Setup';
        $dados['GeneralSettings'] = $GeneralSettings;
        $dados['countrys'] = DB::table('pais')->get();

        $dados['provincias'] = Provincia::where('pais_id', 6)->get();

        return view('admin.configuracoes.general_setting', $dados);
    }

    public function databaseBackup()
    {
        $backup = \Artisan::call('db:backup');
        Session::flash('success', "Database backup save Successfully");
        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        
        $configuracaoGeral = ConfiguracaoGeral::findOrfail($id);

        $endereco = Endereco::where('id', $configuracaoGeral->endereco_id)->first();

        $endereco->numero = addslashes($request->numero);
        $endereco->rua = addslashes($request->rua);
        $endereco->bairro_id = $request->bairro_id;
        $endereco->municipio_id = $request->municipio_id;

        $endereco->save();

        if ($endereco->save()) {

            $configuracaoGeral->nome_escritorio = addslashes($request->cmp_name);
            $configuracaoGeral->endereco_id = $endereco->id;
            $configuracaoGeral->pincode = $request->pincode;
            //----------LOGO image--------
            if ($request->hasFile('logo')) {

                if ($configuracaoGeral->logo_img != '') {

                    $imageUnlink = public_path() . config('constants.LOGO_FOLDER_PATH') . '/' . $configuracaoGeral->logo_img;
                    if (file_exists($imageUnlink)) {
                        unlink($imageUnlink);
                    }
                    $configuracaoGeral->logo_img = '';
                }

                $image = $request->file('logo');

                $name = time() . '_' . rand(1, 4) . $image->getClientOriginalName();

                $destinationPath = public_path() . config('constants.LOGO_FOLDER_PATH');
                $image->move($destinationPath, $name);
                $configuracaoGeral->logo_img = $name;
            }
            //---------------------favicon  Image --------------

            if ($request->hasFile('favicon')) {

                if ($configuracaoGeral->favicon_img != '') {

                    $imageUnlink = public_path() . config('constants.FAVICON_FOLDER_PATH') . '/' . $configuracaoGeral->favicon_img;
                    if (file_exists($imageUnlink)) {
                        unlink($imageUnlink);
                    }
                    $configuracaoGeral->favicon_img = '';
                }

                $image = $request->file('favicon');

                $name = time() . '_' . rand(1, 4) . $image->getClientOriginalName();

                $destinationPath = public_path() . config('constants.FAVICON_FOLDER_PATH');
                $image->move($destinationPath, $name);
                $configuracaoGeral->favicon_img = $name;
            }

            $configuracaoGeral->save();

            if ($configuracaoGeral->save()) {

                Session::flash('success', "Dados actualizados");
                return redirect()->back();
            }
        }
    }
}
