<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Models\{Cliente, Documento, Processo, Agenda, Admin, ProcessoMembro};
use App\Traits\DatatablTrait;
use DB;

class ClienteController extends Controller
{

    use DatatablTrait;

    private $processo;
    private $cliente;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Processo $processo, Cliente $cliente)
    {
        $this->middleware('auth')
            ->except(['check_user_email_exits', 'check_ndi_exits', 'check_nif_exits']);

        $this->processo = $processo;
        $this->cliente = $cliente;
    }

    public function editarPerfil(Request $request)
    {

        $this->validate($request, [
            'f_name' => 'required',
            'l_name' => 'required',
            'email' => 'required',
            'input_img' => 'sometimes|image',
        ]);


        $user = auth()->user();

        $cliente = $this->cliente->findOrFail($user->cliente->id);


        $nome = addslashes($request->f_name);
        $sobrenome = addslashes($request->l_name);
        $instituicao = addslashes($request->instituicao);
        $nome_pai = addslashes($request->nome_pai);
        $nome_mae = addslashes($request->nome_mae);
        $email = addslashes($request->email);

        $user->update(['email' => $email, 'language' => $request->language]);

        $cliente->nome = $nome;
        $cliente->sobrenome = $sobrenome;
        $cliente->instituicao = $instituicao;
        $cliente->nome_pai = $nome_pai;
        $cliente->nome_mae = $nome_mae;


        //check folder exits if not exit then creat automatic
        $pathCheck = public_path() . config('constants.CLIENT_FOLDER_PATH');
        if (!file_exists($pathCheck)) {
            File::makeDirectory($pathCheck, $mode = 0777, true, true);
        }

        //remove image
        if ($request->is_remove_image == "Yes" && $request->file('image') == "") {

            if ($cliente->foto != '') {
                $imageUnlink = public_path() . config('constants.CLIENT_FOLDER_PATH') . '/' . $cliente->foto;
                if (file_exists($imageUnlink)) {
                    unlink($imageUnlink);
                }
                $cliente->foto = '';
            }
        }

        //profile image upload
        if ($request->hasFile('image')) {

            if ($cliente->foto != '') {

                $imageUnlink = public_path() . config('constants.CLIENT_FOLDER_PATH') . '/' . $cliente->foto;
                if (file_exists($imageUnlink)) {
                    unlink($imageUnlink);
                }
                $cliente->foto = '';
            }

            $data = $request->imagebase64;

            list($type, $data) = explode(';', $data);
            list(, $data) = explode(',', $data);
            $data = base64_decode($data);
            $image_name = time() . '.png';
            $path = public_path() . "/upload/profile/" . $image_name;
            file_put_contents($path, $data);
            $cliente->foto = $image_name;
        }
        //login user id
        // profile_img
        // $client->advocate_id = "2";
        // $client->is_user_type = "STAFF";
        // $client->is_activated = "1";

        $cliente->save();


        return back()->with('success', "Perfil actualizado.");
    }

    public function check_user_email_exits(Request $request)
    {

        if ($request->id == "") {
            $count = Utilizador::where('email', $request->email)->count();
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

    public function check_nif_exits(Request $request)
    {

        if ($request->id == "") {
            $count = Cliente::where('nif', $request->nif)->count();
            if ($count == 0) {
                return 'true';
            } else {
                return 'false';
            }
        } else {
            $count = Cliente::where('nif', $request->nif)
                ->where('id', '<>', $request->id)
                ->count();
            if ($count == 0) {
                return 'true';
            } else {
                return 'false';
            }
        }
    }

    public function check_ndi_exits(Request $request)
    {

        if ($request->id == "") {
            $count = Documento::where('ndi', $request->ndi)->count();
            if ($count == 0) {
                return 'true';
            } else {
                return 'false';
            }
        } else {
            $count = Documento::where('ndi', $request->ndi)
                ->where('id', '<>', $request->id)
                ->count();
            if ($count == 0) {
                return 'true';
            } else {
                return 'false';
            }
        }
    }

    public function caseListByClientId()
    {
        $user = auth()->user();

        if ($user->user_type != 'Cliente')
            return back();

        $cliente = $user->cliente;

        $totalCourtCase = $this->processo->where('cliente_id', $cliente->id)->count();

        return view('cliente.processos', compact('totalCourtCase'));
    }

    public function client_case_list(Request $request)
    {

        $user = auth()->user();
        $isEdit = $user->can('case_edit');
        $isDelete = $user->can('case_delete');
        /*
          |----------------
          | Listing colomns
          |----------------
         */

        $columns = array(
            0 => 'case_id',
            1 => 'first_name',
            6 => 'is_active'
        );


        $cond = array('p.cliente_id' => $user->cliente->id);

        $totalData = DB::table('processo AS p')
            ->leftJoin('cliente AS cl', 'cl.id', '=', 'p.cliente_id')
            ->leftJoin('tipoprocesso AS tp', 'tp.id', '=', 'p.tipoprocesso_id')
            ->leftJoin('estadoprocesso AS s', 's.id', '=', 'p.estado')
            ->leftJoin('tribunal AS tb', 'tb.id', '=', 'p.tribunal_id')
            ->leftJoin('juiz AS j', 'j.id', '=', 'p.juiz_id')
            ->leftJoin('seccao', 'seccao.id', '=', 'p.seccao_id')
            ->select(
                'p.id AS case_id',
                'seccao.nome AS seccao',
                'p.no_processo',
                'p.client_position',
                'p.party_name',
                'p.party_lawyer',
                'p.prioridade',
                'tp.designacao AS caseType',
                's.estado',
                'tb.nome AS tribunal',
                'j.nome AS juiz',
                'cl.nome',
                'cl.sobrenome',
                'cl.instituicao',
                'cl.tipo AS tipo_cliente',
                'p.updated_by',
                'cl.id AS advo_client_id'
            )
            ->where($cond)
            ->count();

        $totalFiltered = $totalData;
        $totalRec = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {

            $processos = DB::table('processo AS p')
                ->leftJoin('cliente AS cl', 'cl.id', '=', 'p.cliente_id')
                ->leftJoin('tipoprocesso AS tp', 'tp.id', '=', 'p.tipoprocesso_id')
                ->leftJoin('estadoprocesso AS s', 's.id', '=', 'p.estado')
                ->leftJoin('tribunal AS t', 't.id', '=', 'p.tribunal_id')
                ->leftJoin('juiz AS j', 'j.id', '=', 'p.juiz_id')
                ->leftJoin('seccao', 'seccao.id', '=', 'p.seccao_id')
                ->leftJoin('intervdesignacao AS idcl', 'idcl.id', '=', 'p.client_position')
                ->leftJoin('areaprocessual AS ap', 'ap.id', '=', 'p.areaprocessual_id')
                ->select(
                    'p.id AS case_id',
                    'p.no_interno',
                    'p.no_processo',
                    'p.areaprocessual_id',
                    'ap.designacao AS areaprocessual',
                    'seccao.nome AS seccao',
                    'idcl.designacao AS client_position',
                    'p.party_name',
                    'p.party_lawyer',
                    'p.prioridade',
                    'j.nome AS juiz',
                    'tp.designacao AS caseType',
                    's.estado',
                    't.nome AS tribunal',
                    'cl.nome',
                    'cl.sobrenome',
                    'cl.instituicao',
                    'cl.tipo AS tipo_cliente',
                    'p.updated_by',
                    'cl.id AS advo_client_id',
                    'p.activo'
                )
                ->where($cond)
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

            $processos = DB::table('processo AS p')
                ->leftJoin('cliente AS cl', 'cl.id', '=', 'p.cliente_id')
                ->leftJoin('tipoprocesso AS tp', 'tp.id', '=', 'p.tipoprocesso_id')
                ->leftJoin('estadoprocesso AS s', 's.id', '=', 'p.estado')
                ->leftJoin('tribunal AS t', 't.id', '=', 'p.tribunal_id')
                ->leftJoin('juiz AS j', 'j.id', '=', 'p.juiz_id')
                ->leftJoin('seccao', 'seccao.id', '=', 'p.seccao_id')
                ->leftJoin('intervdesignacao AS idcl', 'idcl.id', '=', 'p.client_position')
                ->leftJoin('areaprocessual AS ap', 'ap.id', '=', 'p.areaprocessual_id')
                ->select(
                    'p.id AS case_id',
                    'p.no_interno',
                    'p.no_processo',
                    'p.areaprocessual_id',
                    'ap.designacao AS areaprocessual',
                    'seccao.nome AS seccao',
                    'idcl.designacao AS client_position',
                    'p.party_name',
                    'p.party_lawyer',
                    'p.prioridade',
                    'j.nome AS juiz',
                    'tp.designacao AS caseType',
                    's.estado',
                    't.nome AS tribunal',
                    'cl.nome',
                    'cl.sobrenome',
                    'cl.instituicao',
                    'cl.tipo AS tipo_cliente',
                    'p.updated_by',
                    'cl.id AS advo_client_id',
                    'p.activo'
                )
                // ->where('case.advocate_id',$advocate_id)
                ->where($cond)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = DB::table('processo AS p')
                ->leftJoin('cliente AS cl', 'cl.id', '=', 'p.cliente_id')
                ->leftJoin('tipoprocesso AS tp', 'tp.id', '=', 'p.tipoprocesso_id')
                ->leftJoin('estadoprocesso AS s', 's.id', '=', 'p.estado')
                ->leftJoin('tribunal AS tb', 'tb.id', '=', 'p.tribunal_id')
                ->leftJoin('juiz AS j', 'j.id', '=', 'p.juiz_id')
                ->leftJoin('seccao', 'seccao.id', '=', 'p.seccao_id')
                ->select(
                    'p.id AS case_id',
                    'seccao.nome AS seccao',
                    'p.no_processo',
                    'p.id',
                    'p.party_name',
                    'p.party_lawyer',
                    'p.prioridade',
                    'tp.designacao AS caseType',
                    's.estado',
                    'tb.nome AS tribunal',
                    'j.nome AS juiz',
                    'cl.nome',
                    'cl.sobrenome',
                    'cl.instituicao',
                    'cl.tipo AS tipo_cliente',
                    'p.updated_by',
                    'cl.id AS advo_client_id'
                )
                ->where($cond)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->count();
        }
        /*
          |----------------------------------------------------------------------------------------------------------------------------------
          | Creating json array with all records based on input from front end site like all,searcheded,pagination record (i.e 10,20,50,100).
          |----------------------------------------------------------------------------------------------------------------------------------
         */
        $data = array();

        if (!empty($processos)) {
            foreach ($processos as $key => $processo) {
                /**
                 * For HTMl action option like edit and delete
                 */
                $show = route('processo.show', encrypt($processo->case_id));

                /**
                 * -/End
                 */

                $first = $processo->party_name;

                $class = ($processo->prioridade == 'High') ? 'fa fa-star' : (($processo->prioridade == 'Média') ? 'fa fa-star-half-o' : 'fa fa-star-o');

                if (empty($request->input('search.value'))) {
                    $final = $totalRec - $start;
                    $nestedData['id'] = $final;
                    $totalRec--;
                } else {
                    $start++;
                    $nestedData['id'] = $start;
                }

                $advogados = $this->getMembers($processo->case_id);

                $totalComentarios = $this->getTotalComentario($processo->case_id);

                $tipoProcesso = ($processo->areaprocessual_id != 4) ? 'Forma de processo' : 'Tipo de ac&ccedil;&atilde;o';

                if ($isEdit) {
                    $nestedData['name'] = '<div style="font-size:15px;" class="clinthead text-primary">
                       <a  class="text-primary" href="javascript:void(0);" onclick="change_case_important(' . $processo->case_id . ')"><i class="text-primary ' . $class . '" aria-hidden="true"></i></a>'
                        . '&nbsp;<a  class="text-primary" href="' . $show . '">' . htmlspecialchars($processo->no_processo) . '</a></div>
                                        <p class="clinttittle">Forma de processo: <b>' . htmlspecialchars($processo->caseType) . '</b></p>';
                } else {
                    $nestedData['name'] = '<div style="font-size:15px;"  class="clinthead text-primary"><a class="text-primary" href="javascript:void(0);" ><i class="text-primary ' . $class . '" aria-hidden="true"></i></a>'
                        . '&nbsp;<a  class="text-primary" href="' . $show . '">' . htmlspecialchars($processo->no_processo) . '</a></div>
                                        <p class="clinttittle">Qualidade: <b>' . htmlspecialchars($processo->client_position) . '</b></p>
                                        <p class="clinttittle">Natureza: <b>' . htmlspecialchars($processo->areaprocessual) . '</b></p>
                                        <p class="clinttittle">' . $tipoProcesso . ': <b>' . htmlspecialchars($processo->caseType) . '</b></p>
                                        <p class="clinttittle">N&ordm; interno: <b>' . str_pad($processo->no_interno, 7, '0', STR_PAD_LEFT) . 'BSA</b></p>
                                        <p class="clinttittle"><b>Advogado(s)</b></p>
                                        ' . $advogados;
                }


                $nestedData['court'] = '<p class="currenttittle">Tribunal :<b> ' . htmlspecialchars($processo->tribunal) . '</b></p>
                                        <p class="currenttittle">Secção :<b> ' . htmlspecialchars($processo->seccao) . '</b></p>
                                        <p class="currenttittle">Magistrado(a) :<b> ' . htmlspecialchars($processo->juiz) . '</b></p>';

                $nestedData['case'] = '<p class="currenttittle">' . htmlspecialchars($first) . ' <br/><p>';


                $nestedData['status'] = htmlspecialchars($processo->estado);

                $nestedData['options'] = $this->action([
                    'view' => route('processo.show', encrypt($processo->case_id)),
                    'documento' => route('processo.docs', encrypt($processo->case_id)),
                    'comentario' => route('processo.comentarios', encrypt($processo->case_id)),
                    'total_comentario' => $totalComentarios
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

    public function ajaxCalander()
    {
        $cliente = auth()->user()->cliente;

        $agendas = Agenda::select('id', 'assunto AS title', DB::raw('DATE_FORMAT(data, "%d-%m-%Y") as start'), DB::raw('DATE_FORMAT(data, "%d-%m-%Y") as end'))
            ->where('activo', 'OPEN')
            ->where('cliente_id', $cliente->id)
            ->get();

        return response()->json($agendas);
    }

    public function agendar(Request $request)
    {

        $agenda = new Agenda();

        $cliente = auth()->user()->cliente;

        $agenda->cliente_id = $cliente->id;
        $agenda->assunto = addslashes($request->assunto);
        $agenda->type = 'exists';
        $agenda->data = $request->data;
        $agenda->hora = date('H:i:s', strtotime($request->hora));
        $agenda->telefone = $cliente->telefone;
        $agenda->observacao = addslashes($request->nota);

        $agenda->save();

        return redirect('admin/dashboard')->with('success', "Agenda criada.");
    }

    public function getMembers($id)
    {
        $getTaskMemberArr = ProcessoMembro::where('processo_id', $id)->pluck('membro');

        $getmulti = Admin::whereIn('id', $getTaskMemberArr)->get();
        $con = "<div style='display: flex;''>";

        foreach ($getmulti as $key => $value) {
            $con .= '<div title="' . $value->pessoasingular->nome . ' ' . $value->pessoasingular->sobrenome . '" data-letters="' . ucfirst(substr($value->pessoasingular->nome, 0, 1)) . '"> </div>';
        }
        $con .= "</div>";

        return $con;
    }

    public function getTotalComentario($cod_processo)
    {
        $processo = $this->processo->with('comentarios')->find($cod_processo);

        return $processo->comentarios->count();
    }
    public function getDayAppointments(Request $request)
    {
        $date = $request->selected_date ?? date('Y-m-d');

        $appointments = DB::table('agenda AS a')
            ->leftJoin('cliente AS ac', 'ac.id', '=', 'a.cliente_id')
            ->leftJoin('agendamento_consultas AS acs', 'acs.agenda_id','=', 'a.id')
            ->leftJoin('agendamento_reuniaos AS ar', 'ar.agenda_id', '=', 'a.id')
            ->select(
                'a.id',
                'a.activo as status',
                'a.hora AS app_time',
                'a.type',
                'acs.vc_tipo as tipo',
                'ar.vc_motivo as motivo'
            )

            ->where('a.cliente_id', auth()->user()->cliente->id)
            ->whereDate('a.data', $date)
            ->where('a.activo', 'OPEN')
            ->get();

        $data = [];
        foreach ($appointments as $appointment) {
            $data[] = [
                'time' => date('H:i', strtotime($appointment->app_time)),
                'name' => ($appointment->tipo) ? $appointment->tipo: $appointment->motivo,
                'status' => $appointment->status,

            ];
        }

        return response()->json([
            'success' => true,
            'data' => $data,
            'total' => count($data)
        ]);
    }
}
