<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fornecedor extends Model
{
    protected $table = 'fornecedor';
    
    public $timestamps = false;
    
    
    public function getFullNameAttribute()
    {
        return ($this->tipo == 'P') ? (ucfirst($this->nome) . ' ' . ucfirst($this->sobrenome)) : $this->company_name;
    }
    
    
    public function endereco()
    {
        return $this->belongsTo(Endereco::class, 'endereco_id');
    }

}
