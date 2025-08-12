<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemFactura extends Model
{
    protected $table = 'item_factura';
    
    public $timestamps = false;
    
    public function servico()
   {
       return $this->hasOne(Servico::class, 'id', 'servico_id');
   }
}
