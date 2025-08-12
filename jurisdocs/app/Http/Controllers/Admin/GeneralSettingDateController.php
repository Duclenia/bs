<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// use App\Model\State;
use Session;
use App\Models\Admin;
use App\Models\ConfiguracaoGeral;
use DB;
use Camroncade\Timezone\Facades\Timezone;

class GeneralSettingDateController extends Controller
{
    //
    public function index()
    {


       $user = auth()->user();  
        if(! $user->can('general_setting_edit')){
            abort(403, 'Acção não autorizada.');
        }

        //'Asia/Kolkata'
        $this->data['timezone'] = DB::table('fusohorario')->get();
        $GeneralSettings  = ConfiguracaoGeral::findOrfail(1); 
        $this->data['title'] = 'Mail Setup';
        $this->data['GeneralSettings'] = $GeneralSettings;   
        $this->data['countrys'] = DB::table('pais')->get();
        
    	return view('admin.configuracoes.general_setting_date', $this->data);
    }


    public function update(Request $request,$id)
    {


        $GeneralSettings  = ConfiguracaoGeral::findOrfail($id);
     
        $GeneralSettings->formato_data  = $request->forment;
        $GeneralSettings->timezone  = $request->timezone;
      
        $GeneralSettings->save();

        Session::flash('success',"Save Successfully");
       return redirect()->back();

    }
}
