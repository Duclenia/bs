<?php

namespace App;

use App\Models\Agenda;
use Illuminate\Database\Eloquent\Model;

class AgendamentoReuniao extends Model
{
  protected $fillable = [
        'vc_entidade', 'vc_motivo', 'link_reuniao' ,'meeting_id','join_url','start_url' , 'vc_pataforma','agenda_id', 'vc_nota', 'it_termo'
    ];

    public function agenda()
    {
        return $this->belongsTo(Agenda::class, 'agenda_id', 'id');
    }

}
