<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// use App\Model\State;
use Session;
use App\Models\Mailsetup;

class SmtpController extends Controller
{
    //
    public function index()
    {

       $user = auth()->user();  
        if(! $user->can('general_setting_edit'))
            return redirect()->back();
        
        
        $mailsetup  = Mailsetup::findOrfail(1); 
        $dados['title'] = 'Mail Setup';
        $dados['mailsetup'] = $mailsetup;
        
    	return view('admin.configuracoes.mail_setup', $dados);
    }


    public function update(Request $request,$id)
    {
       
        $mailsetup  = Mailsetup::findOrfail($id);
        $mailsetup->mail_email     = addslashes($request->email);
        $mailsetup->mail_port      = $request->smtp_port;
        $mailsetup->mail_host      = $request->mail_host;
        $mailsetup->mail_username  = $request->smtp_username;
        $mailsetup->mail_password  = $request->smtp_password;
        $mailsetup->mail_driver  = $request->mail_driver;
        $mailsetup->mail_encryption  = $request->mail_encryption;
        $mailsetup->save();

        Session::flash('success',"Save Successfully");
       return redirect()->back();

    }
}
