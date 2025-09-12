<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    protected $table = 'agenda';
    protected $fillable = [
        'advogado_id',
        'cliente_id',
        'data',
        'hora',
        'type',
        'telefone',
        'nome',
        'observacao',
        'activo',
        'vc_plataforma',
        'join_url',
        'start_url',
        'email',
        'vc_caminho_pdf',
        'custo'
    ];

    public function advogado()
    {
        return $this->belongsTo(User::class, 'advogado_id');
    }
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }
}
