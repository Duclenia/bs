<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    protected $table = 'factura';
    protected $fillable = [
        'cliente_id',
        'agenda_id',
        'factura_no',
        'sub_total_amount',
        'tax_amount',
        'total_amount',
        'due_date',
        'inv_date',
        'remarks',
        'tax_type',
        'tax_id',
        'json_content',
        'invoice_created_by',
        'activo',
        'despesa_id',
        'status'
    ];

    public $timestamps = false;


    public function itensFactura()
    {
        return $this->hasMany(ItemFactura::class, 'factura_id', 'id');
    }

    public function cliente()
    {
        return $this->hasOne(Cliente::class, 'id', 'cliente_id');
    }
}
