<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;


class Cliente extends Model
{
    protected $table = 'cliente';
    
    public $timestamps = false;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 'tipo', 'nome', 'sobrenome', 'instituicao', 'sexo','estado_civil','nif','documento_id',
                           'data_nascimento','nome_pai','nome_mae','telefone','alternate_no',
                           'endereco','pais_id','provincia_id','municipio_id', 'user_id', 'codigo_verificacao'
    ];
    
    
    public function getFullNameAttribute()
    {
        return ($this->tipo == 2) ? ucfirst($this->nome. ' '.$this->sobrenome) : $this->instituicao;
    }
    
    public function getNameAttribute()
    {
        return $name = ucfirst($this->attributes['nome'] .' '.$this->attributes['sobrenome']);

    }
    
    public function pais()
    {
        return $this->belongsTo(Pais::class, 'pais_id');
    }

    public function provincia()
    {
        return $this->belongsTo(Provincia::class, 'provincia_id');
    }

    public function municipio()
    {
        return $this->belongsTo(Municipio::class, 'municipio_id');
    }
    
    public function processos()
    {
        return $this->hasMany(Processo::class);
    }
    
    public function utilizador()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function documento()
    {
        return $this->belongsTo(Documento::class, 'documento_id');
    }
}
