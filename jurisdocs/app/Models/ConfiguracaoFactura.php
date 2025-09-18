<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfiguracaoFactura extends Model
{
    protected $table = 'configuracao_factura';
    protected $fillable=[
        'formato_factura',
        'prefixo',
        'client_note',
        'termos_condicoes',
        'factura_no',
        'receipt_no'
    ];

    public $timestamps = false;
}
