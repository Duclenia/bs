<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CrimEnquad;
use App\Traits\DatatablTrait;
use Validator;

class CrimEnquadController extends Controller
{
    use DatatablTrait;
    
    private $crimEnquad;
    
    public function __construct(CrimEnquad $crimEnquad)
    {
        
        $this->crimEnquad = $crimEnquad;
    }
    
    public function index()
    {
        $user = auth()->user();
        if (!$user->can('listar_crime_enquad'))
            return redirect()->back();

        return view('admin.configuracoes.crime_enquad.crime_enquad');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        return response()->json([
                    'html' => view('admin.configuracoes.crime_enquad.crime_enquad_create')->render()
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
                    'crime_enquad' => 'required'    
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        
        $crimEnquad = $this->crimEnquad->create(['designacao' => addslashes($request->crime_enquad)]);

        if ($crimEnquad) {
            
            return response()->json([
                        'success' => true,
                        'message' => 'Enquadramento registado',
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
        $crimeEnquad = $this->crimEnquad->findOrFail($id);
        
        return response()->json([
                    'html' => view('admin.configuracoes.crime_enquad.crime_enquad_edit', compact('crimeEnquad'))->render()
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
                    'crimEnquad' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        
        $crimEnquad = $this->crimEnquad->findOrFail($id);

        $crimEnquad->update(['designacao' => htmlspecialchars($request->crimEnquad)]);

        return response()->json([
                    'success' => true,
                    'message' => 'Dado actualizado.',
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
    
    public function listarCrimEnquad(Request $request)
    {
        $user = auth()->user();

        $isEdit = $user->can('editar_crime_enquad');
        $isDelete = $user->can('eliminar_crime_enquad');

        // Listing column to show
        $columns = array(
            0 => 'id',
            1 => 'designacao',
            2 => 'action'
        );


        $totalData = CrimEnquad::count();
        $totalRec = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $customcollections = CrimEnquad::when($search, function ($query, $search) {
                    return $query->where('designacao', 'LIKE', "%{$search}%");
                });

        $totalFiltered = $customcollections->count();

        $customcollections = $customcollections->offset($start)->limit($limit)->orderBy($order, $dir)->get();

        $data = [];

        foreach ($customcollections as $key => $item) {

            if (empty($request->input('search.value'))) {
                $final = $totalRec - $start;
                $row['id'] = $final;
                $totalRec--;
            } else {
                $start++;
                $row['id'] = $start;
            }


            $row['designacao'] = htmlspecialchars($item->designacao);

            if ($isEdit == "1" || $isDelete == "1") {

                $row['action'] = $this->action([
                    'edit_modal' => collect([
                        'id' => $item->id,
                        'action' => route('crime-enquad.edit', $item->id),
                        'target' => '#addtag'
                    ]),
                    'edit_permission' => $isEdit,
                    'delete' => collect([
                        'id' => $item->id,
                        'action' => route('crime-enquad.destroy', $item->id),
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
    
    public function checkExistEnquadramentoCrime(Request $request)
    {
        if ($request->id == "") {
            
            $count = $this->crimEnquad->where('designacao', addslashes($request->enquadramento))->count();
            if ($count == 0) {
                return 'true';
            } else {
                return 'false';
            }
        } else {
            $count = $this->crimEnquad->where('designacao', addslashes($request->enquadramento))
                ->where('id', '<>', $request->id)
                ->count();
            if ($count == 0) {
                return 'true';
            } else {
                return 'false';
            }
        }
    }
}
