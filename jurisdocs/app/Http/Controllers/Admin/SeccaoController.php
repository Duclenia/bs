<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Seccao;
use App\Traits\DatatablTrait;
use App\Models\AreaProcessual;
use App\Models\TribunalAreaprocessual;
use App\Models\TribunalAreaprocessualSeccao;
use Validator;

class SeccaoController extends Controller
{

    use DatatablTrait;

    private $areaprocessual;
    private $seccao;
    private $taps;

    public function __construct(AreaProcessual $areaprocessual, Seccao $seccao, TribunalAreaprocessualSeccao $taps) {
        $this->areaprocessual = $areaprocessual;
        $this->seccao = $seccao;
        $this->taps = $taps;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        if (!$user->can('listar_seccao'))
            return redirect()->back();

        return view('admin.configuracoes.seccao.seccao');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dado['areasprocessuais'] = $this->areaprocessual->all();

        return response()->json([
                    'html' => view('admin.configuracoes.seccao.seccao_create', $dado)->render()
        ]);
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
                    'seccao' => 'required',
                    'areaprocessual' => 'required',
                    'tribunal' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $tap = TribunalAreaprocessual::where('areaprocessual_id', addslashes($request->areaprocessual))
                                    ->where('tribunal_id', $request->tribunal)
                                    ->first();

        $seccao = $this->seccao->create(['nome' => htmlspecialchars($request->seccao),
            'areaprocessual_id' => addslashes($request->areaprocessual)
        ]);

        $taps = $this->taps->create(['trib_areaprocessual' => $tap->id, 'seccao_id' => $seccao->id]);

        if ($taps) {
            return response()->json([
                        'success' => true,
                        'message' => 'Secção registada',
                            ], 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $dados['seccao'] = $this->seccao->findOrFail($id);
        
        $dados['areasprocessuais'] = $this->areaprocessual->all();

        //dd($dados['seccao']->tribunais);

        return response()->json([
                    'html' => view('admin.configuracoes.seccao.seccao_edit', $dados)->render()
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
                    'seccao' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $seccao = $this->seccao->findOrFail($id);

        $seccao->update(['nome' => htmlspecialchars($request->seccao)]);

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
    public function destroy($id)
    {
        
    }

    public function caseStatusList(Request $request)
    {

        $user = auth()->user();

        $isEdit = $user->can('section_edit');
        $isDelete = $user->can('section_delete');

        // Listing column to show
        $columns = array(
            0 => 'id',
            1 => 'section_name',
            2 => 'action'
        );


        $totalData = Seccao::count();
        $totalRec = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $customcollections = Seccao::when($search, function ($query, $search) {
                    return $query->where('nome', 'LIKE', "%{$search}%");
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

            $row['section_name'] = htmlspecialchars($item->nome);

            if ($isEdit == "1" || $isDelete == "1") {

                $row['action'] = $this->action([
                    'edit_modal' => collect([
                        'id' => $item->id,
                        'action' => route('seccao.edit', $item->id),
                        'target' => '#addtag'
                    ]),
                    'edit_permission' => $isEdit,
                    'delete' => collect([
                        'id' => $item->id,
                        'action' => route('seccao.destroy', $item->id),
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

}
