<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Role;
use DB;

class PermissaoController extends Controller {

    private $role;

    public function __construct(Role $role)
    {
        $this->role = $role;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //
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
        $this->data['title'] = "Permition";
        $data['role_id'] = $id;

        $funcao = $this->role->findOrFail($id);

        $data['funcao'] = htmlspecialchars($funcao->nome);

        $permissions = DB::table('role_permissions')
                        ->select('roles.nome as role_name', 'permission_id AS permissao_id', 'permissions.nome as permission_name', 'permissions.*', 'roles.*')
                        ->leftJoin('roles', 'roles.id', '=', 'role_permissions.role_id')
                        ->rightJoin('permissions', 'permissions.id', '=', 'role_permissions.permission_id')
                        ->where('roles.id', $id)->get();

        $data['permissions_array'] = $permissions->pluck('permissao_id');
        
        return view('admin.funcao.permissao', $data);
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
        // dd($request->all());
        $role = $this->role->findOrFail($id);

        $permissoes = ($request->has('permission')) ? $request->permission : array();

        $role->permissoes()->detach();

        if (count($permissoes) > 0) {
            $role->permissoes()->sync($permissoes);
        }

        session()->flash('success', "Permissão actualizada.");
        return redirect()->route('funcao.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }

}
