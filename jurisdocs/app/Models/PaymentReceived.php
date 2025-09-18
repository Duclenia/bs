<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentReceived extends Model
{
    protected $table = 'payment_receiveds';

    protected $fillable = [
        'cliente_id',
        'factura_id',
        'amount',
        'cheque_date',
        'receiving_date',
        'payment_type',
        'reference_number',
        'note',
        'comprovativo',
        'status',
        'payment_received_by',
    ];
}
