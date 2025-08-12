<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    protected $table = 'factura';
    
    public $timestamps = false;
    
    
    public function itensFactura()
    {
        return $this->hasMany(ItemFactura::class,'factura_id','id');
    }

     public function cliente()
    {
        return $this->hasOne(Cliente::class,'id','cliente_id');
    }
}
