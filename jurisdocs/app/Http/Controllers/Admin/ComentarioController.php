<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Comentario;
use App\Models\Processo;
use App\Traits\DatatablTrait;
use Validator;

class ComentarioController extends Controller
{
    use DatatablTrait;
    
    private $processo;
    private $comentario;

    public function __construct(Processo $processo, Comentario $comentario) {
        
        $this->processo = $processo;
        $this->comentario = $comentario;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($cod_processo)
    {
        $processo = $this->processo->with('comentarios')->findOrFail(decrypt($cod_processo));
        
        return view('admin.processo.comentario.index', compact('processo'));
    }
    
    public function getComentarios(Request $request)
    {
         $user = auth()->user();

        // Listing column to show
        $columns = array(
            0 => 'id',
            1 => 'descricao',
            2 => 'data_criacao',
            3 => 'action'
        );
        
        $totalData = Comentario::where('processo_id', $request->cod_processo)->count();
        $totalRec = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $customcollections = Comentario::where('processo_id', $request->cod_processo)
                                       ->when($search, function ($query, $search) {
            return $query->where('conteudo', 'LIKE', "%{$search}%");
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

            
            $row['descricao'] = htmlspecialchars($item->conteudo);
            $row['data_criacao'] = formatarData($item->created_at, $format = 'd-m-Y H:i:s');
            
            $row['autor'] = '';


            $row['action'] = $this->action([
                'edit_modal' => collect([
                    'id' => $item->id,
                    'action' => route('comentario.edit', encrypt($item->id)),
                    'target' => '#addtag'
                ]),
                'edit_permission' => $user->id == $item->comentado_por,
                'delete' => collect([
                    'id' => $item->id,
                    'action' => route('auto.destroy', encrypt($item->id)),
                ]),
                'delete_permission' => $user->id == $item->comentado_por,
            ]);

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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($cod_processo)
    {
        $processo = $this->processo->findOrFail(decrypt($cod_processo));
        
        return response()->json([
                    'html' => view('admin.processo.comentario.comentario_create', compact('processo'))->render()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $cod_processo)
    {
        $processo = $this->processo->findOrFail(decrypt($cod_processo));
        
        $comentario = new Comentario();
        
        $comentario->conteudo = addslashes($request->comentario);
        $comentario->processo_id = $processo->id;
        $comentario->comentado_por = auth()->id();
        $comentario->save();
        
        if($comentario->save()){
            
            return response()->json([
                        'success' => true,
                        'message' => 'Comentário registado',
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $comentario = $this->comentario->with('processo')->findOrFail(decrypt($id));
        
        return response()->json([
                    'html' => view('admin.processo.comentario.comentario_edit', compact('comentario'))->render()
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
                    'comentario' => 'required'
        ]);

        if ($validator->fails())
            return response()->json(['errors' => $validator->errors()->all()]);
        
        $comentario = $this->comentario->findorfail($id);
        
        $comentario->conteudo = addslashes($request->comentario);
        $comentario->save();
        
        return response()->json([
                    'success' => true,
                    'message' => 'Dados actualizados',
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
        //
    }
}
