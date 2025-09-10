<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Gate;
use App\Http\Controllers\Controller;
use Session;
use DB;
use App\Models\Admin;
use File;
use Mail;
use Illuminate\Support\Facades\Hash;
use App\Helpers\LogActivity;
use App\Models\{HorarioAdvogado, Processo, TarefaMembro, PessoaSingular, Pessoa, Role};
use Validator;
use App\Traits\DatatablTrait;
use App\User;

class clientUserController extends Controller
{

    use DatatablTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        //        if (Gate::denies('case_list'))
        //            return redirect()->back();

        $user = auth()->user();

        if ($user->user_type != "SuperAdmin")
            return back();

        return view('admin.team-members.team_member');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = auth()->user();
        if ($user->user_type != "SuperAdmin")
            return back();

        //$data['country']  = DB::table('countries')->where('id',101)->first();
        $data['states'] = DB::table('provincia')->where('pais_id', 6)->get();
        $data['funcoes'] = Role::where('id', '!=', '1')->get();

        return view('admin.team-members.team_member_create', $data);
    }
    public function createHorarioAdvogado()
    {

        $user = auth()->user();
        if ($user->user_type != "SuperAdmin")
            return back();

        $data['advogados'] = User::where('user_type', 'ADV')->get();
        return view('admin.team-members.create_horario_advogado');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        // $advocate_id = $this->getLoginUserId();

        $validator = Validator::make($request->all(), [
            'role' => 'required',
            'f_name' => 'required',
            'l_name' => 'required',
            'sexo' => 'required',
            'ddn' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required',
            'cnm_password' => 'required|same:password',
            'input_img' => 'sometimes|image',
        ]);
        if ($validator->passes()) {

            $pessoa = new Pessoa();
            $user = new User();
            $ps = new PessoaSingular();

            $client = new Admin;
            //check folder exits if not exit then creat automatic
            $pathCheck = public_path() . config('constants.CLIENT_FOLDER_PATH');
            if (!file_exists($pathCheck)) {
                File::makeDirectory($pathCheck, $mode = 0777, true, true);
            }

            //profile image upload
            /* if ($request->hasFile('image')) {
              $image = $request->file('image');
              $name = time().'_'.rand(1,4).$image->getClientOriginalName();
              $destinationPath = public_path() . config('constants.CLIENT_FOLDER_PATH');
              $image->move($destinationPath, $name);
              $client->profile_img=$name;
              } */
            if ($request->hasFile('image')) {
                $data = $request->imagebase64;

                list($type, $data) = explode(';', $data);
                list(, $data) = explode(',', $data);

                $data = base64_decode($data);
                $image_name = time() . '.png';

                $path = public_path() . "/upload/profile/" . $image_name;

                file_put_contents($path, $data);

                $pessoa->foto = $image_name;
            }
            $pwd = 'No'; //config('constants.CLIENT_PASSWORD_FOR_JR_ADVO');
            // profile_img
            //$client->advocate_id    = $advocate_id;

            $user->email = addslashes($request->email);
            $user->password = Hash::make($request->password);

            if ($request->role == '4'):
                $user->user_type = 'Admin';

            elseif ($request->role == '3'):
                $user->user_type = 'ADV';
            endif;

            $user->save();

            $pessoa->tipo = 2;
            $pessoa->save();

            if ($pessoa->save() && $user->save()) {

                $ps->nome = addslashes($request->f_name);
                $ps->sobrenome = addslashes($request->l_name);
                $ps->sexo = $request->sexo;
                $ps->data_nascimento = ($request->ddn != '') ? date('Y-m-d', strtotime(LogActivity::commonDateFromat($request->ddn))) : null;
                $ps->pessoa_id = $pessoa->id;

                $ps->save();

                if ($ps->save()) {
                    $client->pessoasingular_id = $ps->id;
                    $client->user_id = $user->id;
                    $client->is_user_type = "STAFF";
                    $client->is_activated = "1";

                    $client->save();
                }

                if ($client->save())
                    $user->funcoes()->sync($request->role);
            }

            //Session::flash('success',"Team member created successfully.");
            return redirect()->route('client_user.index')->with('success', "Membro da equipa criado com sucesso.");
        }
        return back()->with('errors', $validator->errors());
    }

    public function completeSetupWizard(Request $request)
    {

        $this->validate($request, [
            'firstname' => 'required',
            'lastname' => 'required',
            'address' => 'required',
            'country_id' => 'required',
            'state_id' => 'required',
            'city_id' => 'required',
            'email' => 'required',
            'mobile_no' => 'required',
            'registration_no' => 'required',
            'associated_name' => 'required',
            'zipcode' => 'required',
            // 'image' => 'sometimes|image',
        ]);

        $client = Admin::findOrFail($request->advocate_id);
        //check folder exits if not exit then creat automatic
        $pathCheck = public_path() . config('constants.CLIENT_FOLDER_PATH');
        if (!file_exists($pathCheck)) {
            File::makeDirectory($pathCheck, $mode = 0777, true, true);
        }

        //profile image upload
        /* if ($request->hasFile('image')) {
          $image = $request->file('image');
          $name = time().'_'.rand(1,4).$image->getClientOriginalName();
          $destinationPath = public_path() . config('constants.CLIENT_FOLDER_PATH');
          $image->move($destinationPath, $name);
          $client->profile_img=$name;
          } */
        if ($request->hasFile('image')) {
            $data = $request->imagebase64;

            list($type, $data) = explode(';', $data);
            list(, $data) = explode(',', $data);

            $data = base64_decode($data);
            $image_name = time() . '.png';

            $path = public_path() . "/upload/profile/" . $image_name;

            file_put_contents($path, $data);

            $client->profile_img = $image_name;
        }
        $client->is_user_type = "ADVOCATE";
        $client->is_account_setup = "1";
        $client->first_name = $request->firstname;
        $client->last_name = $request->lastname;
        $client->mobile = $request->mobile_no;
        $client->registration_no = $request->registration_no;
        $client->associated_name = $request->associated_name;
        $client->zipcode = $request->zipcode;
        $client->address = $request->address;
        $client->country_id = $request->country_id;
        $client->state_id = $request->state_id;
        $client->city_id = $request->city_id;
        $client->save();

        $redirect_url = '#';
        $activity = '';
        LogActivity::addToLog('Account setup. ', $activity, $redirect_url);

        Session::flash('success', "Account setup successfully.");
        return redirect()->route('dashboard.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function check_user_name_exits(Request $request)
    {

        if ($request->id == "") {
            $count = Admin::where('name', $request->name)->count();
            if ($count == 0) {
                return 'true';
            } else {
                return 'false';
            }
        } else {
            $count = Admin::where('name', '=', $request->name)
                ->where('id', '<>', $request->id)
                ->count();
            if ($count == 0) {
                return 'true';
            } else {
                return 'false';
            }
        }
    }

    public function check_user_email_exits(Request $request)
    {

        if ($request->id == "") {
            $count = User::where('email', $request->email)->count();
            if ($count == 0) {
                return 'true';
            } else {
                return 'false';
            }
        } else {

            $count = User::where('email', $request->email)
                ->where('id', '<>', $request->id)
                ->count();
            if ($count == 0) {
                return 'true';
            } else {
                return 'false';
            }
        }
    }

    public function show($id)
    {
        //
    }

    public function clientUserList(Request $request)
    {
        /*
          |----------------
          | Listing colomns
          |----------------
         */

        $columns = array(
            0 => 'id',
            1 => 'name',
            2 => 'email',
            3 => 'mobile',
            4 => 'role_id',
            5 => 'is_active',
        );

        //$advocate_id = $this->getLoginUserId();

        $totalData = DB::table('admin AS ad')
            ->leftJoin('users AS ut', 'ad.user_id', '=', 'ut.id')
            ->leftJoin('user_roles AS uf', 'uf.user_id', '=', 'ut.id')
            ->leftJoin('roles AS f', 'f.id', '=', 'uf.role_id')
            ->leftJoin('pessoasingular AS ps', 'ps.id', '=', 'ad.pessoasingular_id')
            ->select('ad.id AS id', 'ps.nome AS first_name', 'ps.sobrenome AS last_name', 'ut.email AS email', 'ad.activo AS is_active', 'f.nome as role')
            ->where('ut.user_type', 'User')
            ->orWhere('ut.user_type', 'Admin')
            ->orWhere('ut.user_type', 'ADV')
            ->count();

        $totalFiltered = $totalData;
        $totalRec = $totalData;
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $cats = DB::table('admin AS ad')
                ->leftJoin('users AS ut', 'ad.user_id', '=', 'ut.id')
                ->leftJoin('user_roles AS uf', 'uf.user_id', '=', 'ut.id')
                ->leftJoin('roles AS f', 'f.id', '=', 'uf.role_id')
                ->leftJoin('pessoasingular AS ps', 'ps.id', '=', 'ad.pessoasingular_id')
                ->select('ad.id AS id', 'ps.nome AS first_name', 'ps.sobrenome AS last_name', 'ut.email AS email', 'ad.activo AS is_active', 'f.nome as role')
                ->where('ut.user_type', 'User')
                ->orWhere('ut.user_type', 'Admin')
                ->orWhere('ut.user_type', 'ADV')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            /*
              |--------------------------------------------
              | For table search filterfrom frontend site.
              |--------------------------------------------
             */
            $search = $request->input('search.value');

            $cats = DB::table('admin AS ad')
                ->leftJoin('users AS ut', 'ad.user_id', '=', 'ut.id')
                ->leftJoin('user_roles AS uf', 'uf.user_id', '=', 'ut.id')
                ->leftJoin('roles AS f', 'f.id', '=', 'uf.role_id')
                ->leftJoin('pessoasingular AS ps', 'ps.id', '=', 'ad.pessoasingular_id')
                ->select('ad.id AS id', 'ps.nome AS first_name', 'ps.sobrenome AS last_name', 'ut.email AS email', 'ad.activo AS is_active', 'f.nome as role')
                ->where('ut.user_type', 'User')
                ->orWhere('ut.user_type', 'Admin')
                ->orWhere('ut.user_type', 'ADV')
                ->where(function ($cats) use ($search) {
                    $cats->where('ad.id', 'LIKE', "%{$search}%")
                        ->orWhere('ps.nome', 'LIKE', "%{$search}%")
                        ->orWhere('ps.sobrenome', 'LIKE', "%{$search}%")
                        ->orWhere('ut.email', 'LIKE', "%{$search}%");
                })->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = DB::table('admin AS ad')
                ->leftJoin('users AS ut', 'ad.user_id', '=', 'ut.id')
                ->leftJoin('user_roles AS uf', 'uf.user_id', '=', 'ut.id')
                ->leftJoin('roles AS f', 'f.id', '=', 'uf.role_id')
                ->leftJoin('pessoasingular AS ps', 'ps.id', '=', 'ad.pessoasingular_id')
                ->select('ad.id AS id', 'ps.nome AS first_name', 'ps.sobrenome AS last_name', 'ut.email AS email', 'ad.activo AS is_active', 'f.nome as role')
                ->where('ut.user_type', 'User')
                ->orWhere('ut.user_type', 'Admin')
                ->orWhere('ut.user_type', 'ADV')
                ->where(function ($cats) use ($search) {
                    $cats->where('ad.id', 'LIKE', "%{$search}%")
                        ->orWhere('ps.nome', 'LIKE', "%{$search}%")
                        ->orWhere('ps.sobrenome', 'LIKE', "%{$search}%")
                        ->orWhere('ut.email', 'LIKE', "%{$search}%");
                })->count();
        }
        /*
          |----------------------------------------------------------------------------------------------------------------------------------
          | Creating json array with all records based on input from front end site like all,searcheded,pagination record (i.e 10,20,50,100).
          |----------------------------------------------------------------------------------------------------------------------------------
         */
        $data = array();
        if (!empty($cats)) {
            foreach ($cats as $cat) {
                /**
                 * For HTMl action option like edit and delete
                 */
                $edit = route('client_user.edit', $cat->id);
                $token = csrf_field();

                // $action_delete = '"'.route('sale-Admin.destroy', $cat->id).'"';
                $action_delete = route('client_user.destroy', $cat->id);

                $delete = "<form action='{$action_delete}' method='post' onsubmit ='return  confirmDelete()'>
                {$token}
                            <input name='_method' type='hidden' value='DELETE'>
                            <button class='dropdown-item text-center' type='submit'  style='background: transparent;
    border: none;'><i class='fa fa-trash'></i>&nbsp;&nbsp;Delete</button>
                          </form>";

                /**
                 * -/End
                 */
                $nestedData['status'] = $this->status($cat->is_active, $cat->id, route('client_user.status'));


                if (empty($request->input('search.value'))) {
                    $final = $totalRec - $start;
                    $nestedData['id'] = $final;
                    $totalRec--;
                } else {
                    $start++;
                    $nestedData['id'] = $start;
                }
                $nestedData['email'] = htmlspecialchars($cat->email);
                $nestedData['mobile'] = '';
                $nestedData['role_id'] = $cat->role ?? null;
                $nestedData['name'] = htmlspecialchars(mb_strtoupper($cat->first_name . ' ' . $cat->last_name));

                //                  $nestedData['options']="<div class='dropdown btn-right'>
                //     <button class='btn btn-primary dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                //     </button>
                //     <div class='dropdown-menu dropdown-menu-right' aria-labelledby='dropdownMenuButton' x-placement='top-end' style='position: absolute; transform: translate3d(-132px, -258px, 0px); top: 0px; left: 0px; will-change: transform;' x-out-of-boundaries>
                //         <a class='dropdown-item text-center' href='{$edit}'>Edit</a>
                //         <a class='dropdown-item text-center' href='javascript:void(0);'>$delete</a>
                //     </div>
                // </div>";

                $nestedData['options'] = $this->action([
                    'edit' => route('client_user.edit', $cat->id),

                    'delete_permission' => '1',
                    'edit_permission' => '1',
                    'delete' => collect([
                        'id' => $cat->id,
                        'action' => route('client_user.destroy', $cat->id),
                    ]),
                ]);
                $data[] = $nestedData;
            }
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = auth()->user();
        if ($user->user_type != "SuperAdmin")
            return back();

        $data['roles'] = Role::where('id', '!=', '1')->get();

        // $data['country']   = DB::table('countries')->where('id',101)->first();
        $data['adm'] = Admin::with(['utilizador', 'pessoasingular.pessoa'])->find($id);

        // $data['states'] = DB::table('states')->where('country_id',$data['users']->country_id)->get();
        // $data['citys'] = DB::table('cities')->where('state_id',$data['users']->state_id)->get();
        return view('admin.team-members.team_member_edit', $data);
    }


    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'role' => 'required',
            'f_name' => 'required',
            'l_name' => 'required',
            'sexo'   => 'required',
            'input_img' => 'sometimes|image',
        ]);

        if ($validator->passes()) {
            $client = Admin::with(['pessoasingular.pessoa', 'utilizador'])->findOrFail($id);

            $peS = $client->pessoasingular;
            $pessoa = $peS->pessoa;
            $utilizador = $client->utilizador;

            //check folder exits if not exit then creat automatic
            $pathCheck = public_path() . config('constants.CLIENT_FOLDER_PATH');
            if (!file_exists($pathCheck)) {
                File::makeDirectory($pathCheck, $mode = 0777, true, true);
            }

            //remove image
            if ($request->is_remove_image == "Yes" && $request->file('image') == "") {

                if ($pessoa->foto != '') {
                    $imageUnlink = public_path() . config('constants.CLIENT_FOLDER_PATH') . '/' . $pessoa->foto;
                    if (file_exists($imageUnlink)) {
                        unlink($imageUnlink);
                    }
                    $pessoa->foto = '';
                }
            }

            //profile image upload
            if ($request->hasFile('image')) {

                if ($pessoa->foto != '') {

                    $imageUnlink = public_path() . config('constants.CLIENT_FOLDER_PATH') . '/' . $pessoa->foto;
                    if (file_exists($imageUnlink)) {
                        unlink($imageUnlink);
                    }
                    $pessoa->foto = '';
                }

                $data = $request->imagebase64;

                list($type, $data) = explode(';', $data);
                list(, $data) = explode(',', $data);

                $data = base64_decode($data);
                $image_name = time() . '.png';
                $path = public_path() . "/upload/profile/" . $image_name;

                file_put_contents($path, $data);

                $pessoa->foto = $image_name;

                /* $image = $request->file('image');
                  $name = time().'_'.rand(1,4).$image->getClientOriginalName();
                  $destinationPath = public_path() . config('constants.CLIENT_FOLDER_PATH');
                  $image->move($destinationPath, $name);
                  $client->profile_img=$name; */
            }

            $pessoa->save();

            // $clientName = $request->f_name.' '.$request->l_name;
            //login user id

            $peS->nome = addslashes($request->f_name);
            $peS->sobrenome = addslashes($request->l_name);
            $peS->nome_pai = addslashes($request->nome_pai);
            $peS->nome_mae = addslashes($request->nome_mae);
            $peS->sexo = $request->sexo;
            $peS->data_nascimento = ($request->ddn != '') ? date('Y-m-d', strtotime(LogActivity::commonDateFromat($request->ddn))) : null;
            $peS->save();

            if ($request->chk_pass == 'yes') {

                $utilizador->password = Hash::make($request->password);
            }

            if ($request->role == '4'):
                $utilizador->user_type = 'Admin';

            elseif ($request->role == '3'):
                $utilizador->user_type = 'ADV';
            endif;

            $utilizador->save();

            //$client->password   = 'no';
            //$client->email      = $request->email;
            //$client->mobile     = $request->mobile;

            // Remove old user roles
            $utilizador->funcoes()->detach();
            // Add role to user admin_role

            if ($utilizador->save()) {
                $utilizador->funcoes()->sync($request->role);
            }
            return redirect()->route('client_user.index')->with('success', "Dados actualizados.");
        }

        return back()->with('errors', $validator->errors());
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function insertJrAdvoToDBbyAjax(Request $request)
    {

        $jr_advo_email = $request->email;
        $check = DB::table('admin')->where('email', $jr_advo_email)->count();
        if ($check > 0) {
            echo 'exists';
        } else {
            $pwd = 'No'; //config('constants.CLIENT_PASSWORD_FOR_JR_ADVO');
            $insert_row = Admin::create([
                'name' => $request->name,
                'advocate_id' => $request->advocate_id,
                'email' => $request->email,
                'password' => $pwd,
                'is_user_type' => 'STAFF',
            ]);
            $my_id = $insert_row->id;

            //generate a random string using Laravel's str_random helper
            $insert_arr = $insert_row->toArray();
            $insert_arr['link'] = str_random(30);
            if ($insert_row) {
                //Get email template content and replcae with value
                $verifyLink = url('admin/user/invitation', $insert_arr['link']);
                $replace = array('{{link}}' => $verifyLink, '{{email}}' => $insert_arr['email'], '{{name}}' => $insert_arr['name']);
                $email_template = DB::table('emails')->where('id', 4)->first();
                $insert_arr['templateContent'] = $this->strReplaceAssoc($replace, $email_template->message_boddy);

                DB::table('invites')->insert(['admin_id' => $my_id, 'advocate_id' => $request->advocate_id, 'token' => $insert_arr['link']]);
                Mail::send('emails.invitation', $insert_arr, function ($message) use ($insert_arr) {
                    $message->to($insert_arr['email']);
                    $message->subject('AdvocateDairy - Invitation to Access Advocate Dairy');
                });
                echo '<div class="row" id="item_' . $my_id . '">
                        <div class="col-sm-5">
                                <div class="form-group label-floating">
                                        <label class="control-label">Name</label>
                                        <input name="added_name" id="added_name" type="text" class="form-control" value="' . $request->name . '" >
                                </div>
                        </div>
                        <div class="col-sm-5">
                                <div class="form-group label-floating">
                                        <label class="control-label">Email Address</label>
                                        <input name="added_email" id="added_email" type="email" class="form-control" value="' . $request->email . '" >
                                </div>
                        </div>
                        <div class="col-sm-2">
                                <div class="form-group label-floating">
                                        <input data-repeater-delete class="btn btn-fill btn-danger del_button" id="del-' . $my_id . '" type="button" value="Delete"/>
                                </div>
                        </div>
                </div>';
            }
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $CourtCase = Processo::where('updated_by', $id)->count();
        $CaseLog = CaseLog::where('updated_by', $id)->count();
        $task = TarefaMembro::where('employee_id', $id)->count();
        $caseMenber = CaseMember::where('employee_id', $id)->count();

        if ($CourtCase > 0 || $CaseLog > 0 || $task > 0 || $caseMenber > 0) {
            //Session::flash('error',"You can't delete this team member because its used in other modules.");
            return response()->json([
                'error' => true,
                'message' => 'You cant delete this team member because its used in other modules',
            ], 400);
        } else {
            $client = Admin::find($id);
            $clientName = $client->first_name . ' ' . $client->last_name;

            if ($client->profile_img != '') {

                $imageUnlink = public_path() . config('constants.CLIENT_FOLDER_PATH') . '/' . $client->profile_img;
                if (file_exists($imageUnlink)) {
                    unlink($imageUnlink);
                }
            }
            $client->delete();

            //activity log
            // $redirect_url = '';
            // $activity = $clientName;
            // LogActivity::addToLog('Team member deleted ',$activity,$redirect_url);
            // Session::flash('success',"Client deleted successfully.");
        }
        return response()->json([
            'success' => true,
            'message' => 'Team member deleted successfully.',
        ], 200);
    }

    public function changeStatus(Request $request)
    {

        $statuscode = 400;
        $client = Admin::findOrFail($request->id);
        $client->activo = $request->status == 'true' ? 'S' : 'N';

        if ($client->save()) {
            $statuscode = 200;
        }

        $status = $request->status == 'true' ? 'activado' : 'desactivado';
        $message = 'Estado ' . $status;

        return response()->json([
            'success' => true,
            'message' => $message
        ], $statuscode);
    }

    public function storeHorarioAdvogado(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'day_off' => 'required',
            'day_of_week' => 'required'
        ]);

        if ($validator->fails())
            return response()->json(['errors' => $validator->errors()->all()]);

        // Processar breaks: converter textarea em array
        $breaks = null;
        if (!empty($request->breaks)) {
            $breaks = array_filter(explode("\n", $request->breaks));
        }

        $dia = HorarioAdvogado::where('day_of_week', $request->day_of_week)->where('id_advogado', $request->advocate_id)->first();
        if ($dia) {
            $horario = HorarioAdvogado::where('day_of_week', $request->day_of_week)->update(
                [
                    'start_time' => $request->start_time,
                    'end_time' => $request->end_time,
                    'interval_minutes' => $request->interval_minutes,
                    'breaks' => $breaks,
                    'day_off' => $request->day_off,
                ]
            );
        } else {
            $horario = HorarioAdvogado::create(
                [
                    'day_of_week' => $request->day_of_week,
                    'start_time' => $request->start_time,
                    'end_time' => $request->end_time,
                    'interval_minutes' => $request->interval_minutes,
                    'breaks' => $breaks,
                    'day_off' => $request->day_off,
                    'advogado_id' => $request->advocate_id
                ]
            );
        }
        if ($horario) {

            return redirect()->route('horario.index')->with('success', "Horario adicionado com sucesso.");
        }
    }
    public function getByDay(Request $request)
    {
        $horario = HorarioAdvogado::where('day_of_week', $request->day_of_week)->where('advogado_id', $request->id)->first();

        if ($horario) {
            return response()->json([
                'success' => true,
                'data' => $horario
            ]);
        } else {
            return response()->json([
                'success' => false,
                'data' => null
            ]);
        }
    }
    public function getAvailableTimes($date, $id)
    {
        $dayOfWeek = \Carbon\Carbon::parse($date)->format('l'); // Monday, Tuesday, etc.

        $schedule = HorarioAdvogado::where('day_of_week', $dayOfWeek)->where('advogado_id', $id)->where('day_off', 1)->first();

        if (!$schedule) {
            return response()->json(['available_times' => []]);
        }

        $start = \Carbon\Carbon::parse($schedule->start_time);
        $end = \Carbon\Carbon::parse($schedule->end_time);
        $interval = $schedule->interval_minutes;
        $breaks = $schedule->breaks ?? [];

        // Buscar agendamentos já marcados para esta data
        $bookedTimes = \DB::table('agenda')
            ->whereDate('data', $date)
            ->pluck('hora')
            ->map(function ($time) {
                return \Carbon\Carbon::parse($time)->format('H:i');
            })
            ->toArray();

        $times = [];

        while ($start->lt($end)) {
            $slotStart = $start->format('H:i');
            $inBreak = false;

            // Verificar se está em pausa
            foreach ($breaks as $break) {
                if (strpos($break, '-') !== false) {
                    [$bStart, $bEnd] = explode('-', $break);
                    if ($start->between(\Carbon\Carbon::parse($bStart), \Carbon\Carbon::parse($bEnd))) {
                        $inBreak = true;
                        break;
                    }
                }
            }

            // Verificar se não está em pausa e não está agendado
            if (!$inBreak && !in_array($slotStart, $bookedTimes)) {
                $times[] = $slotStart;
            }

            $start->addMinutes($interval);
        }

        return response()->json(['available_times' => $times]);
    }
    public function getBlockedDates($id)
    {
        // Buscar dias da semana com day_off = 0 (não trabalha)
        $blockedDaysOfWeek = HorarioAdvogado::where('day_off', 0)->where('advogado_id', $id)
            ->pluck('day_of_week')
            ->toArray();

        // Mapear dias da semana para números (0 = Domingo, 1 = Segunda, etc.)
        $dayMapping = [
            'sunday' => 0,
            'monday' => 1,
            'tuesday' => 2,
            'wednesday' => 3,
            'thursday' => 4,
            'friday' => 5,
            'saturday' => 6
        ];

        $blockedDayNumbers = [];
        foreach ($blockedDaysOfWeek as $day) {
            if (isset($dayMapping[$day])) {
                $blockedDayNumbers[] = $dayMapping[$day];
            }
        }

        return response()->json([
            'blocked_days' => $blockedDayNumbers,
            'min_date' => date('Y-m-d') // Data mínima é hoje
        ]);
    }
}
