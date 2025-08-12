<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Traits\DatatablTrait;

class FuncaoController extends Controller
{
    use DatatablTrait;
    
    private $role;
    
    public function __construct(Role $role) {
        
        $this->role = $role;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $userType = auth()->user()->user_type;
        if ($userType != "SuperAdmin")
            return back();

        return view('admin.funcao.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      
        $userType = auth()->user()->user_type;
        if ($userType != "SuperAdmin")
            return back();
        
        return response()->json([
            'html' => view('admin.funcao.create')->render()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $role = new Role();
        $role->nome = $request->slug;
        $role->descricao = addslashes($request->description);
        
        $role->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Função criada.'
        ], 200);
    }

    public function roleList(Request $request)
    {
        
        // Listing column to show
        $columns = array(
            0 => 'id',
            1 => 'nome'
        );

        $totalData = $this->role->where('id', '!=', '1')->count();
        $totalRec = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');


        $customcollections = $this->role->where('id', '!=', '1')
            ->when($search, function ($query, $search) {
                return $query->where('nome', 'LIKE', "%{$search}%");
            });

        $totalFiltered = $customcollections->count();

        $customcollections = $customcollections->offset($start)
                                         ->limit($limit)
                                         ->orderBy($order, $dir)
                                         ->get();

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

            $row['slug'] = htmlspecialchars($item->nome);

            $row['action'] = $this->action([
                'delete_permission' => '1',
                'edit_permission' => '1',
                'edit_modal' => collect([
                    'id' => $item->id,
                    'action' => route('funcao.edit', $item->id),
                    'target' => '#addtag'
                ]),
                'delete' => collect([
                    'id' => $item->id,
                    'action' => route('funcao.destroy', $item->id),
                ]),
                'permission' => route('permission.edit', $item->id),
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
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data['role_id'] = $id;
        return view('admin.funcao.permission', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $userType = auth()->user()->user_type;
        if ($userType != "SuperAdmin") {
            return back();
        }
        $this->data['role'] = $this->role->findOrFail($id);
        return response()->json([
            'html' => view('admin.funcao.edit', $this->data)->render()
        ]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        $role = $this->role->findOrFail($id);
        $role->nome = $request->slug;
        $role->descricao = addslashes($request->description);
        $role->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Função actualizada.'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = $this->role->findOrFail($id);

        // if role has no permissions then delete
        if ($role->permissoes()->count() > 0) {

            return response()->json([
                'error' => true,
                'message' => 'Permission has already assign to this role.to delete this role you need to free this role from permission than after you able to delete this role.'
            ], 400);
        }
        
        $role->delete();

        return response()->json([
            'success' => true,
            'message' => 'Função eliminada.'
        ], 200);

    }
}
