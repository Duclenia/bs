<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Exception;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Session;
use App\Models\TipoPessoa;

class PerfilController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
           
        $user = auth()->user();
        
        if ( $user->user_type != "Cliente") {
             
            return view('admin.perfil', compact('user'));
        }else{
            
            $tipospessoas = TipoPessoa::all();
            
            return view('cliente.perfil', compact('user', 'tipospessoas'));
        }
    }

    public function login()
    {
        return view('admin.login');
    }

    public function changePassword()
    {
        return view('admin.change_password');
    }

    public function change_pass()
    {
        return view('admin.change_password');
    }

    public function changedPassword(Request $request)
    {
        $this->validate($request, [
            'old' => 'required',
            'new' => 'required|string|min:8|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
            'confirm' => 'required|same:new'
        ]);

        $current_password = $request->old;
        $password = $request->new;

        if (Hash::check($current_password, auth()->user()->password)) {
            $password = Hash::make($password);
            try {
                auth()->user()->update(['password' => $password]);
                
                $flag = TRUE;
            } catch (Exception $e) {
                $flag = FALSE;
            }
            if ($flag) {
                Session::flash('success', __('Password changed successfully.'));

                return redirect('/admin/admin-profile');
            } else {
                Session::flash('error', __('Unable to process request this time. Try again later.'));

                return redirect('/admin/admin-profile');
            }
        } else {
            Session::flash('error', __('Your current password do not match our record.'));

            return redirect('/admin/admin-profile');
        }
    }

    public function forgot()
    {
        return view('admin.forgot');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request) {
        //
    }

    public function editProfile(Request $request) {
        
        $this->validate($request, [
            'f_name' => 'required',
            'l_name' => 'required',
            'email' => 'required',
            'input_img' => 'sometimes|image',
        ]);


        $user = auth()->user();
        
        $client = Admin::with('pessoasingular.pessoa')->find($user->admin->id);
        
        $nome = addslashes($request->f_name);
        $sobrenome = addslashes($request->l_name);
        $nome_pai  = addslashes($request->nome_pai);
        $nome_mae  = addslashes($request->nome_mae);
        $email = addslashes($request->email);
        
        $user->update(['email' => $email,'language' => $request->language]);
        
        
        $client->pessoasingular->update(['nome' => $nome,
                                         'sobrenome' => $sobrenome,
                                         'sexo' => $request->sexo,
                                         'nome_pai'  => $nome_pai,
                                         'nome_mae'  => $nome_mae
                                       ]);

        //check folder exits if not exit then creat automatic
        $pathCheck = public_path() . config('constants.CLIENT_FOLDER_PATH');
        if (!file_exists($pathCheck)) {
            File::makeDirectory($pathCheck, $mode = 0777, true, true);
        }

        //remove image
        if ($request->is_remove_image == "Yes" && $request->file('image') == "") {

            if ($client->pessoasingular->pessoa->foto != '')
            {
                $imageUnlink = public_path() . config('constants.CLIENT_FOLDER_PATH') . '/' . $client->profile_img;
                if (file_exists($imageUnlink)) {
                    unlink($imageUnlink);
                }
                $client->pessoasingular->pessoa->foto = '';
            }
        }

        //profile image upload
        if ($request->hasFile('image'))
        {

            if ($client->pessoasingular->pessoa->foto != '')
            {

                $imageUnlink = public_path() . config('constants.CLIENT_FOLDER_PATH') . '/' . $client->profile_img;
                if (file_exists($imageUnlink)) {
                    unlink($imageUnlink);
                }
                $client->pessoasingular->pessoa->foto = '';
            }

            $data = $request->imagebase64;

            list($type, $data) = explode(';', $data);
            list(, $data) = explode(',', $data);
            $data = base64_decode($data);
            $image_name = time() . '.png';
            $path = public_path() . "/upload/profile/" . $image_name;
            file_put_contents($path, $data);
            $client->pessoasingular->pessoa->foto = $image_name;
        }
       
        
        $client->save();

        return back()->with('success', __('Profile updated successfully.'));
    }

    public function imageCropPost(Request $request)
    {
        $id = $request->id;
        $data = $request->image;
        list($type, $data) = explode(';', $data);
        list(, $data) = explode(',', $data);

        $data = base64_decode($data);
        $image_name = time() . '.png';
        $path = public_path() . "/upload/profile/" . $image_name;
        file_put_contents($path, $data);
        
        $client = Admin::with('pessoasingular.pessoa')->find($id);
        
        if ($client->pessoasingular->pessoa->foto != '')
        {
            $imageUnlink = public_path() . config('constants.CLIENT_FOLDER_PATH') . '/' . $client->profile_img;
            if (file_exists($imageUnlink)) {
                unlink($imageUnlink);
            }
            $client->pessoasingular->pessoa->foto = '';
        }
        $client->pessoasingular->pessoa->foto = $image_name;
        
        $client->pessoasingular->pessoa->update();

        return response()->json(['success' => 'done']);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

}
