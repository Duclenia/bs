<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AgendamentoReuniao extends Model
{
  protected $fillable = [
        'vc_entidade', 'vc_motivo', 'vc_pataforma','agenda_id', 'vc_nota', 'it_termo'
    ];

}
