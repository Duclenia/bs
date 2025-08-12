<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\DatatablTrait;
use Validator;
use App\Models\CrimEnquad;
use App\Models\CrimeSubEnquad;
use DB;

class CrimeSubEnquadController extends Controller
{
    
    use DatatablTrait;
    
    private $crimEnquad;
    private $crimeSubEnquad;
    
    public function __construct(CrimEnquad $crimEnquad, CrimeSubEnquad $crimeSubEnquad)
    {
        $this->crimEnquad = $crimEnquad;
        $this->crimeSubEnquad = $crimeSubEnquad;
    }
    
    public function index()
    {
        $user = auth()->user();
        if (!$user->can('listar_crime_sub_enquad'))
            return redirect()->back();

        return view('admin.configuracoes.crime_sub_enquad.crime_sub_enquad');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $crimesEnquad = $this->crimEnquad->all();
        
        return response()->json([
                    'html' => view('admin.configuracoes.crime_sub_enquad.crime_sub_enquad_create', compact('crimesEnquad'))->render()
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
                    'crime_enquad' => 'required',
                    'crime_sub_enquad' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        
        $crimeSubEnquad = $this->crimeSubEnquad->create(['designacao' => addslashes($request->crime_sub_enquad),
                                                         'idEnq' => addslashes($request->crime_enquad)
                                                       ]);
        if ($crimeSubEnquad) {
            
            return response()->json([
                        'success' => true,
                        'message' => 'Sub-enquadramento do crime registado',
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
        $dados['crimeSubEnquad'] = $this->crimeSubEnquad->findOrFail($id);
        
        $dados['crimEnquads'] = $this->crimEnquad->all();
        
        
        return response()->json([
                    'html' => view('admin.configuracoes.crime_sub_enquad.crime_sub_enquad_edit', $dados)->render()
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
                    'crimEnquad' => 'required',
                    'crimeSubEnquad' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        
        $crimeSubEnquad = $this->crimeSubEnquad->findOrFail($id);

        $crimeSubEnquad->update(['designacao' => htmlspecialchars($request->crimeSubEnquad),
                                 'idEnq' => $request->crimEnquad ]);

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
        //
    }
    
    public function listarCrimeSubEnquad(Request $request)
    {
        $user = auth()->user();

        $isEdit = $user->can('editar_crime_sub_enquad');
        $isDelete = $user->can('eliminar_crime_sub_enquad');

        // Listing column to show
        $columns = array(
            0 => 'id',
            1 => 'crime_enquad',
            2 => 'crime_sub_enquad',
            3 => 'action'
        );

        $totalData = DB::table('crimsubenquad')
                        ->join('crimenquad', 'crimsubenquad.idEnq', '=', 'crimenquad.id')
                        ->select('crimsubenquad.id AS id', 'crimenquad.designacao AS crimenquad', 'crimsubenquad.designacao AS crimsubenquad')
                        ->count();
        
        $totalRec = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $customcollections = DB::table('crimsubenquad')
                                ->join('crimenquad', 'crimsubenquad.idEnq', '=', 'crimenquad.id')
                                ->select('crimsubenquad.id AS id', 'crimenquad.designacao AS crimenquad', 'crimsubenquad.designacao AS crimsubenquad')
                                ->when($search, function ($query, $search) {
                            $query->where('crimenquad.designacao', 'LIKE', "%{$search}%")
                            ->orWhere('crimsubenquad.designacao', 'LIKE', "%{$search}%");
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


            $row['crime_enquad'] = htmlspecialchars($item->crimenquad);
            
            $row['crime_sub_enquad'] = htmlspecialchars($item->crimsubenquad);

            if ($isEdit == "1" || $isDelete == "1") {

                $row['action'] = $this->action([
                    'edit_modal' => collect([
                        'id' => $item->id,
                        'action' => route('crime-sub-enquad.edit', $item->id),
                        'target' => '#addtag'
                    ]),
                    'edit_permission' => $isEdit,
                    'delete' => collect([
                        'id' => $item->id,
                        'action' => route('crime-sub-enquad.destroy', $item->id),
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
    
    public function checkExistSubEnquadramentoCrime(Request $request)
    {
        if ($request->id == "") {
            
            $count = $this->crimeSubEnquad->where('designacao', addslashes($request->subEnq))->count();
            if ($count == 0) {
                return 'true';
            } else {
                return 'false';
            }
        } else {
            $count = $this->crimeSubEnquad->where('designacao', addslashes($request->subEnq))
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
