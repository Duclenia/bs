<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfiguracaoGeral extends Model
{
    protected $table = 'configuracao_geral';
    
    
    public function endereco()
    {
        return $this->belongsTo(Endereco::class, 'endereco_id');
    }
}
