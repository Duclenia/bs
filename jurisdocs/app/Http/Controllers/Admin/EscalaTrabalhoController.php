<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use DB;

class EscalaTrabalhoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $advogados = DB::table('admin AS ad')
                ->join('pessoasingular AS ps', 'ad.pessoasingular_id', '=', 'ps.id')
                ->select('ad.id AS id', 'ps.nome', 'ps.nome_meio', 'ps.sobrenome')
                ->orderBy('ps.nome', 'asc')
                ->get();
        
        return view('admin.escala_atendimento.index', compact('advogados'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
        //
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
    
    public function getEscalaAtendimento(Request $request)
    {
        
        $escala = DB::table('escala_trabalho AS e')
                ->join('escalatrabalho_diasemana AS ed', 'e.id', '=', 'ed.escala')
                ->select('ed.id AS id', 'ed.hora_inicio', 'ed.hora_fim')
                ->where('e.funcionario', $request->advogado)
                ->orderBy('e.dia', 'asc')
                ->get();
    }
    
}
