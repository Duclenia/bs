<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemFactura extends Model
{
    protected $table = 'item_factura';
    protected $fillable = [
        'factura_id',
        'imposto_id',
        'item_description',
        'iteam_qty',
        'item_amount',
        'item_rate',
        'tax_amount',
        'hsn',
        'servico_id',
    ];

    public $timestamps = false;

    public function servico()
    {
        return $this->hasOne(Servico::class, 'id', 'servico_id');
    }
}
