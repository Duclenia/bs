<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Processo;
use App\Models\Juiz;
use App\Models\AreaProcessual;
use App\Models\TribunalAreaprocessualSeccao;
use Validator;
use App\Traits\DatatablTrait;
use DB;

// use App\Helpers\LogActivity;

class JuizController extends Controller
{

    use DatatablTrait;

    protected $areaprocessual;
    protected $taps;

    public function __construct(AreaProcessual $areaprocessual, TribunalAreaProcessualSeccao $taps) {
        $this->areaprocessual = $areaprocessual;
        $this->taps = $taps;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        $user = auth()->user();
        if (!$user->can('judge_list'))
            return redirect()->back();

        return view('admin.configuracoes.juiz.judge');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $dado['areasprocessuais'] = $this->areaprocessual->all();

        return response()->json([
                    'html' => view('admin.configuracoes.juiz.judge_create', $dado)->render()
        ]);
    }

    public function caseStatusList(Request $request) {

        $user = auth()->user();
        $isEdit = $user->can('judge_edit');
        $isDelete = $user->can('judge_delete');

// Listing column to show
        $columns = array(
            0 => 'id',
            1 => 'judge_name',
            2 => 'tribunal',
            3 => 'seccao',
            4 => 'is_active',
        );


        $totalData = Juiz::count();
        $totalRec = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $customcollections = DB::table('juiz AS j')
                ->join('tribunal_areaprocessual_seccao AS taps', 'taps.id', '=', 'j.tribunal')
                ->join('seccao AS s', 's.id', '=', 'taps.seccao_id')
                ->join('tribunal_areaprocessual AS tap', 'tap.id', '=', 'taps.trib_areaprocessual')
                ->join('tribunal AS t', 't.id', '=', 'tap.tribunal_id')
                ->select('j.id AS id', 'j.nome AS juiz', 'j.activo AS activo', 't.nome AS tribunal', 's.nome AS seccao')
                ->when($search, function ($query, $search) {
            $query->where('j.nome', 'LIKE', "%{$search}%")
            ->orWhere('ep.estado', 'LIKE', "%{$search}%");
        });

        $totalFiltered = $customcollections->count();

        $customcollections = $customcollections->offset($start)->limit($limit)->orderBy($order, $dir)->get();

        $data = [];

        foreach ($customcollections as $key => $item) {

// $row['id'] = $item->id;

            if (empty($request->input('search.value'))) {
                $final = $totalRec - $start;
                $row['id'] = $final;
                $totalRec--;
            } else {
                $start++;
                $row['id'] = $start;
            }


            $row['judge_name'] = htmlspecialchars($item->juiz);

            $row['tribunal'] = htmlspecialchars($item->tribunal);

            $row['seccao'] = htmlspecialchars($item->seccao);

            if ($isEdit == "1") {
                $row['is_active'] = $this->status($item->activo, $item->id, route('judge.status'));
            } else {
                $row['is_active'] = [];
            }

            if ($isEdit == "1" || $isDelete == "1") {

                $row['action'] = $this->action([
                    'edit_modal' => collect([
                        'id' => $item->id,
                        'action' => route('juiz.edit', $item->id),
                        'target' => '#addtag'
                    ]),
                    'edit_permission' => $isEdit,
                    'delete' => collect([
                        'id' => $item->id,
                        'action' => route('juiz.destroy', $item->id),
                    ]),
                    'delete_permission' => $isDelete,
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
            "data" => $data,
        );

        return response()->json($json_data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
                    'judge_name' => 'required',
                    'areaprocessual' => 'required',
                    'tribunal' => 'required',
                    'seccao' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $taps = $this->taps->getTribunalSeccao($request->areaprocessual, $request->tribunal, $request->seccao)[0]->id;

        $juiz = new Juiz();
        $juiz->nome = htmlspecialchars($request->judge_name);
        $juiz->tribunal = $taps;
        $juiz->save();

        return response()->json([
                    'success' => true,
                    'message' => 'Juiz(a) registado(a)',
                        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
//
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {

        $dados['juiz'] = Juiz::findorfail($id);

        $dados['areasprocessuais'] = $this->areaprocessual->all();

        $dados['taps'] = DB::table('tribunal_areaprocessual_seccao AS taps')
                ->join('seccao AS s', 's.id', '=', 'taps.seccao_id')
                ->join('tribunal_areaprocessual AS tap', 'tap.id', '=', 'taps.trib_areaprocessual')
                ->join('tribunal AS t', 't.id', '=', 'tap.tribunal_id')
                ->join('areaprocessual AS ap', 'ap.id', '=', 'tap.areaprocessual_id')
                ->select('ap.id AS areaprocessual_id', 't.id AS tribunal_id', 't.nome AS tribunal', 's.id AS seccao_id', 's.nome AS seccao'
                )
                ->where('taps.id', $dados['juiz']->tribunal)
                ->get();


        return response()->json([
                    'html' => view('admin.configuracoes.juiz.judge_edit', $dados)->render()
        ]);
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

        $validator = Validator::make($request->all(), [
                    'judge_name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $taps = $this->taps->getTribunalSeccao($request->areaprocessual, $request->tribunal, $request->seccao)[0]->id;

        $juiz = Juiz::findorfail($id);
        $juiz->nome = $request->judge_name;
        $juiz->tribunal = $taps;
        $juiz->save();


        return response()->json([
                    'success' => true,
                    'message' => 'Dados actualizados.',
                        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changeStatus(Request $request)
    {
        $statuscode = 400;

        $juiz = Juiz::findOrFail($request->id);
        $juiz->activo = $request->status == 'true' ? 'S' : 'N';

        if ($juiz->save()) {
            $statuscode = 200;
        }
        $status = $request->status == 'Yes' ? 'Yes' : 'No';
        $message = 'Estado do juiz alterado.';

        return response()->json([
                    'success' => true,
                    'message' => $message
                        ], $statuscode);
    }

    public function destroy($id) {

        $count = 0;
        $count += Processo::where('juiz_id', $id)->count();

        if ($count == 0) {
            $row = Juiz::destroy($id);

            return response()->json([
                        'success' => true,
                        'message' => 'Juiz eliminado.'
                            ], 200);
        } else {

            return response()->json([
                        'error' => true,
                        'errormessage' => 'Não é possível eliminar este juiz.'
                            ], 400);
        }
    }

}
