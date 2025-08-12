<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PessoaSingular extends Model
{
    protected $table = 'pessoasingular';
    
    public $timestamps = false;
    
    protected $fillable = [
        'nome', 'nome_meio', 'sobrenome', 'sexo', 'estado_civil', 'data_nascimento', 'nome_pai',
        'nome_mae', 'pais_id', 'municipio_id', 'pessoa_id'
    ];
    
    
    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class, 'pessoa_id');
    }
}
