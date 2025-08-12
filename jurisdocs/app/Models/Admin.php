<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\User;

class Admin extends Model
{
    
    protected $table= 'admin';
    
    
    public function pessoasingular()
    {
        return $this->belongsTo(PessoaSingular::class, 'pessoasingular_id');
    }
    
    
    public function utilizador()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    
    public function listarAdvogados()
    {
        
        $membros = DB::table('admin AS ad')
                                    ->join('pessoasingular AS ps', 'ad.pessoasingular_id', '=', 'ps.id')
                                    ->join('users AS us', 'ad.user_id', '=', 'us.id')
                                    ->where('us.user_type', 'ADV')
                                    ->where('ad.activo', 'S')
                                    ->select('ad.id AS id', 'ps.nome', 'ps.sobrenome')
                                    ->orderBy('ps.nome', 'asc')
                                    ->orderBy('ps.sobrenome', 'asc')
                                    ->get();
        
        return $membros;
        
    }
    
    
    public function listarMembros()
    {
        
        $membros = DB::table('admin AS ad')
                                    ->join('pessoasingular AS ps', 'ad.pessoasingular_id', '=', 'ps.id')
                                    ->join('users AS us', 'ad.user_id', '=', 'us.id')
                                    ->where('us.user_type', 'ADV')
                                    ->orWhere('us.user_type', 'User')
                                    ->where('ad.activo', 'S')
                                    ->select('ad.id AS id', 'ps.nome', 'ps.sobrenome')
                                    ->orderBy('ps.nome', 'asc')
                                    ->orderBy('ps.sobrenome', 'asc')
                                    ->get();
        
        return $membros;
        
    }
}
