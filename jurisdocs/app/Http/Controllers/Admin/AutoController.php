<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Traits\{DatatablTrait,Mensagem};
use App\Models\{Auto,Cliente,Processo};
use Validator;

class AutoController extends Controller
{

    use DatatablTrait;
    use Mensagem;

    private $processo;
    private $auto;

    public function __construct(Processo $processo, Auto $auto)
    {
        $this->processo = $processo;
        $this->auto = $auto;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $processo = $this->processo->with('autos')->findOrFail(decrypt($id));

        return view('admin.processo.auto.index', compact('processo'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $processo = $this->processo->findOrFail(decrypt($id));

        return view('admin.processo.auto.create', compact('processo'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $input = $request->all();

        $processo = $this->processo->findOrFail(decrypt($id));

        $validatedData = $this->validarAnexo($input);

        
        if ($validatedData->passes())
        {
            $documentos = '';
            
            for ($i = 0; $i < count($request->allFiles()['autos']); $i++)
            {

                $arquivo = $request->allFiles()['autos'][$i];

                $auto = new Auto();
                $auto->descricao = $request['descricao'][$i];
                $auto->anexo = $arquivo->store('autos');
                $auto->processo_id = $processo->id;
                $auto->autor = auth()->id();
                $auto->save();
                
                if (count($request->allFiles()['autos']) == 1)
                    $documentos .= $auto->descricao;
                else
                    $documentos .= $auto->descricao. ', ';

                unset($auto);
            }

            $cliente = $processo->cliente;

            if ($cliente->utilizador)
            {

                if ($cliente->utilizador->id != auth()->user()->id)
                    $this->enviarSMS($cliente, $processo, $documentos);
            } else {

                $this->enviarSMS($cliente, $processo, $documentos);
            }

            return redirect()->route('processo.docs', encrypt($processo->id))->with('success', "Arquivo(s) adicionado(s) ao processo com o nº interno " . $processo->no_interno . 'BSA');
        }

        return back()->with('errors', $validatedData->errors());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $headers = ['Content-Type' => 'application/pdf'];

        $auto = $this->auto->findOrFail(decrypt($id));

        $filename = $auto->anexo;

        if (empty($filename))
            return back();

        $path = storage_path('app/' . $filename);

        return response()->file($path, $headers);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
        $auto = $this->auto->findOrFail(decrypt($id));

        return response()->json([
                    'html' => view('admin.processo.auto.auto_edit', compact('auto'))->render()
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
                    'descricao' => 'required'
        ]);

        if ($validator->fails())
            return response()->json(['errors' => $validator->errors()->all()]);
     
        $auto = $this->auto->findOrFail($id);

        if ($request->hasFile('auto') && $request->file('auto')->isValid()) {

            if (Storage::disk('public')->exists($auto->anexo))
                Storage::disk('public')->delete($auto->anexo);

            $arquivo = $request->file('auto');

            $auto->anexo = $arquivo->store('autos');
        }

        $auto->descricao = addslashes($request->descricao);


        if ($auto->save()) {
            return response()->json([
                        'success' => true,
                        'message' => 'Auto actualizado',
                            ], 200);
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
        $auto = $this->auto->findOrFail(decrypt($id));

        if ($auto->delete()) {

            Storage::delete($auto->anexo);

            return response()->json([
                        'success' => true,
                        'message' => 'Auto eliminado.'
                            ], 200);
        }
    }

    public function listarAutos(Request $request)
    {

        $user = auth()->user();

        // Listing column to show
        $columns = array(
            0 => 'id',
            1 => 'descricao',
            2 => 'data_criacao'
        );

        $totalData = Auto::where('processo_id', $request->id)->count();
        $totalRec = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $customcollections = Auto::where('processo_id', $request->id)->when($search, function ($query, $search) {
            return $query->where('descricao', 'LIKE', "%{$search}%");
        });

        $totalFiltered = $customcollections->count();

        $customcollections = $customcollections->offset($start)->limit($limit)->orderBy($order, $dir)->get();

        $data = [];

        foreach ($customcollections as $key => $item)
        {
            // $row['id'] = $item->id;

            if (empty($request->input('search.value')))
            {
                $final = $totalRec - $start;
                $row['id'] = $final;
                $totalRec--;
            } else {
                $start++;
                $row['id'] = $start;
            }

            $row['descricao'] = htmlspecialchars($item->descricao);
            $row['data_criacao'] = formatarData($item->created_at, $format = 'd-m-Y H:i:s');
            
            $row['action'] = $this->action([
                'view_auto' => route('auto.show', encrypt($item->id)),
                'edit_modal' => collect([
                    'id' => $item->id,
                    'action' => route('auto.edit', encrypt($item->id)),
                    'target' => '#addtag'
                ]),
                'edit_permission' => $user->id == $item->autor || $user->user_type != 'Cliente',
                'delete' => collect([
                    'id' => $item->id,
                    'action' => route('auto.destroy', encrypt($item->id)),
                ]),
                'delete_permission' => $user->id == $item->autor || $user->user_type != 'Cliente'
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
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    protected function validarAnexo(array $data)
    {
        
        return Validator::make($data, [
                    'descricao.*' => 'required',
                    'auto.*' => 'required|mimes:pdf'
        ]);
    }

    public function enviarSMS(Cliente $cliente, Processo $processo, $documentos)
    {
        
        if ($cliente->telefone) {

            if (is_null($cliente->codigo_verificacao))
            {
                
//                $tel = explode(' ', $cliente->telefone);
//
//                $telefone = $tel[0] . $tel[1] . $tel[2];

                $autos = $processo->autos()->whereDate('created_at', date('Y-m-d'))->get();
                
                $no_processo = is_null($processo->no_processo) ? ' interno '. str_pad($processo->no_interno, 7, '0', STR_PAD_LEFT) . 'BSA' : $processo->no_processo;

                if ($autos->count())
                {
                    $corpo = " foi juntado o(s) seguinte(s) documento(s): " . $documentos . " ao processo de nro" .$no_processo. ". Visite a sua área reservada no Jurisdocs a partir do link https://jurisdocs.bsa.ao para mais informações. Obrigado!";

                    $mensagem = array('destinatario' => $cliente->FullName, 'corpo_mensagem' => $corpo);

                    $this->alertaDeMensagem($cliente->telefone, $mensagem);
                }
            }
        }
    }
}
