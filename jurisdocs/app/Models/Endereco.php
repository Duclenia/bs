<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Endereco extends Model
{
    protected $table = 'endereco';
    
    public $timestamps = false;
    
    
    public function municipio()
    {
        return $this->belongsTo(Municipio::class, 'municipio_id');
    }
    
    
    public function bairro()
    {
        return $this->belongsTo(Bairro::class);
    }
}
