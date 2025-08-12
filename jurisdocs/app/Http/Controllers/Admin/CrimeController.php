<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\DatatablTrait;
use App\Models\Crime;
use App\Models\CrimEnquad;
use DB;
use Validator;

class CrimeController extends Controller {

    use DatatablTrait;

    private $crimEnquad;
    private $crime;

    public function __construct(CrimEnquad $crimEnquad, Crime $crime) {

        $this->crimEnquad = $crimEnquad;
        $this->crime = $crime;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $user = auth()->user();
        if (!$user->can('listar_crime'))
            return redirect()->back();

        return view('admin.configuracoes.crime.crime');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {

        return response()->json([
                    'html' => view('admin.configuracoes.crime.crime_create')->render()
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
                    'crime' => 'required',
                    'artigo' => 'required',
                    'crime_enquad' => 'required',
                    'crime_sub_enquad' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $crime = $this->crime->create(['designacao' => addslashes($request->crime),
            'artigo' => addslashes($request->artigo),
            'idEnq' => addslashes($request->crime_enquad),
            'idSubEnq' => addslashes($request->crime_sub_enquad)
        ]);

        if ($crime) {

            return response()->json([
                        'success' => true,
                        'message' => 'Tipo de crime registado',
                            ], 200);
        }
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
    public function edit($id)
    {
        
        $crime = $this->crime->with('crimEnquad', 'crimSubEnquad')->findOrFail($id);

        return response()->json([
                    'html' => view('admin.configuracoes.crime.crime_edit', compact('crime'))->render()
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
                    'crime' => 'required',
                    'artigo' => 'required',
                    'crime_enquad' => 'required',
                    'crime_sub_enquad' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $crime = $this->crime->findOrFail($id);

        $crime->update(['designacao' => addslashes($request->crime),
                        'artigo' => addslashes($request->artigo),
                        'idEnq' => addslashes($request->crime_enquad),
                        'idSubEnq' => addslashes($request->crime_sub_enquad)
                       ]);

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
        
    }

    public function listarCrimes(Request $request)
    {
        $user = auth()->user();

        $isEdit = $user->can('editar_crime');
        $isDelete = $user->can('eliminar_crime');

        // Listing column to show
        $columns = array(
            0 => 'id',
            1 => 'tipo_crime',
            2 => 'artigo',
            3 => 'crime_enquad',
            4 => 'crime_sub_enquad',
            5 => 'action'
        );


        $totalData = DB::table('crime AS c')
                ->join('crimenquad', 'c.idEnq', '=', 'crimenquad.id')
                ->join('crimsubenquad', 'c.idSubEnq', '=', 'crimsubenquad.id')
                ->select('c.id', 'c.artigo', 'crimenquad.designacao AS crimenquad', 'crimsubenquad.designacao AS crimsubenquad')
                ->count();

        $totalRec = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $customcollections = DB::table('crime AS c')
                                    ->join('crimenquad', 'c.idEnq', '=', 'crimenquad.id')
                                    ->join('crimsubenquad', 'c.idSubEnq', '=', 'crimsubenquad.id')
                                    ->select('c.id', 'c.designacao AS crime', 'c.artigo', 'crimenquad.designacao AS crimenquad', 'crimsubenquad.designacao AS crimsubenquad')
                                    ->when($search, function ($query, $search) {
                                $query->where('c.designacao', 'LIKE', "%{$search}%")
                                ->orWhere('crimenquad.designacao', 'LIKE', "%{$search}%")
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


            $row['tipo_crime'] = htmlspecialchars($item->crime);
            $row['artigo'] = htmlspecialchars($item->artigo);
            $row['crime_enquad'] = htmlspecialchars($item->crimenquad);
            $row['crime_sub_enquad'] = htmlspecialchars($item->crimsubenquad);

            if ($isEdit == "1" || $isDelete == "1") {

                $row['action'] = $this->action([
                    'edit_modal' => collect([
                        'id' => $item->id,
                        'action' => route('crime.edit', $item->id),
                        'target' => '#addtag'
                    ]),
                    'edit_permission' => $isEdit,
                    'delete' => collect([
                        'id' => $item->id,
                        'action' => route('crime.destroy', $item->id),
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
    
    public function checkExistTipoCrime(Request $request)
    {
        if ($request->id == "") {
            
            $count = $this->crime->where('designacao', addslashes($request->tipo_crime))->count();
            if ($count == 0) {
                return 'true';
            } else {
                return 'false';
            }
        } else {
            $count = $this->crime->where('designacao', addslashes($request->tipo_crime))
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
