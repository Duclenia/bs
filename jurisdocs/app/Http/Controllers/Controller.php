<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use DB;
use App\Models\Imposto;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    public function common_check_exist(Request $request) {

        if ($request->id == "") {
            if ($request->condition_db_field != "") {
                $count = DB::table($request->table)
                        ->where($request->db_field, '=', $request->form_field)
                        ->where($request->condition_db_field, '=', $request->condition_form_field)
                        // ->where('advocate_id',$advocate_id)
                        ->where('activo', 'S')
                        ->count();
            } else {
                $count = DB::table($request->table)
                        ->where($request->db_field, '=', $request->form_field)
                        // ->where('advocate_id',$advocate_id)
                        ->where('activo', 'S')
                        ->count();
            }
            if ($count == 0) {
                return 'true';
            } else {
                return 'false';
            }
        } else {
            if ($request->condition_db_field != "") {
                
                $count = DB::table($request->table)
                        ->where($request->db_field, '=', $request->form_field)
                        ->where('id', '<>', $request->id)
                        ->where($request->condition_db_field, '=', $request->condition_form_field)
                        // ->where('advocate_id',$advocate_id)
                        ->where('activo', 'S')
                        ->count();
            } else {
                $count = DB::table($request->table)
                        ->where($request->db_field, '=', $request->form_field)
                        ->where('id', '<>', $request->id)
                        // ->where('advocate_id',$advocate_id)        
                        ->where('activo', 'S')
                        ->count();
            }

            if ($count == 0) {
                return 'true';
            } else {
                return 'false';
            }
        }
    }

    public function getStateByCountry(Request $request)
    {
        $records = DB::table('provincia')->where('pais_id', $request->id)->get();
        return view('admin.include.modal_get_option', ['records' => $records]);
    }

    public function getCitiesByState(Request $request)
    {
        $records = DB::table('municipio')->where('provincia_id', $request->id)->get();
        return view('admin.include.modal_get_option', ['records' => $records]);
    }

    public function getCaseSubType(Request $request)
    {
        $records = DB::table('case_types')
                ->where('parent_id', $request->id)
                ->where('is_active', 'Yes')
                ->orderBy('case_type_name', 'asc')
                ->get();
        foreach ($records as $record) {
            echo '<option value="' . $record->id . '">' . $record->case_type_name . '</option>';
        }
    }

    public function getCourt(Request $request)
    {

        $records = DB::table('courts')
                ->where('court_type_id', $request->id)
                ->where('is_active', 'Yes')
                ->orderBy('court_name', 'asc')
                ->get();
        foreach ($records as $record) {
            echo '<option value="' . $record->id . '">' . $record->court_name . '</option>';
        }
    }

    public function getData(Request $request) {
        dd($request);
    }

    public function advocateSetUpWizard() {
        $advocate_id = $this->getLoginUserId();
        $detail = Admin::findorfail($advocate_id);
        $country = DB::table('countries')->where('id', 101)->first();
        $states = DB::table('states')->where('country_id', 101)->get();

        return view('admin.client-user.client_setup_wizard', ['detail' => $detail, 'country' => $country, 'advocate_id' => $advocate_id, 'states' => $states]);
    }

    public function getTaxById(Request $request) {


        $tax = $request->id;

        $taxs = Imposto::where('activo', 'S')->where('nome', $tax)->get();


        $thml = '<option value="0" taxsepara="" taxrate="0.00">' . $tax . ' 0 %</option>';
        if (!empty($taxs) && count($taxs)) {
            foreach ($taxs as $key => $value) {
                $thml .= '<option value="' . $value->id . '" taxsepara="' . $value->name . '" taxrate="' . $value->per . '">' . $value->name . ' ' . $value->per . ' %</option>';
            }
        }

        return $thml;
    }

//    public function getLoginUserId() {
//
//        if (Auth::guard('admin')->user()) {
//            $type = auth()->user();
//            if ($type->is_user_type == 'ADVOCATE') {
//                return $type->id;
//            } elseif ($type->is_user_type == 'STAFF') {
//                return $type->advocate_id;
//            }
//        } elseif (Auth::guard('superadmin')->user()) {
//            return 0;
//        }
//    }

    public function getLoginUserNameById($id) {

        if ($id != '') {
            $name = DB::table('admin')->where('id', $id)->first();
            if (!empty($name)) {
                $fullname = $name->first_name . ' ' . $name->last_name;
                return $fullname;
            } else {
                return 'N/A';
            }
        } else {
            return 'N/A';
        }
    }

    public function common_change_state(Request $request)
    {
        $id = $request->id;
        $status = $request->status;
        $table = $request->table;

        $record = DB::table($table)->where('id', $id)->first();

        if ($table == 'agenda') {
            $redirect_url = url('admin/appointment');
            $lableName = 'Appointments';
            $name = $record->activo;
        } else {


            $name = $record->$table_field;
            $lableName = str_replace('_', ' ', $table);
        }
        
        if ($record->activo == 'Yes') {
            $status_flag = 'Activo';
        } else {
            $status_flag = 'Inactivo';
        }
        if ($status == 'Yes') {
            $status_flag_request = 'Activo';
        } else {
            $status_flag_request = 'Inactivo';
        }

        $msg = ucwords($lableName) . '. status has been changed for ' . $name;
        $activity = 'from ' . $status_flag . ' to ' . $status_flag_request;


        $change = DB::table($table)->where('id', $id)->update(['activo' => $status]);

        //activity logs


        if ($change) {
            echo json_encode(array("status" => TRUE));
        } else {
            echo json_encode(array("status" => FALSE));
        }
    }

    public function getClientCasesTotal($client_id)
    {
        $records = DB::table('processo')->where('cliente_id', $client_id)->count();
        return $records;
    }
}
