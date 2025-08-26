<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AgendamentoConsulta extends Model
{
     protected $fillable = [
        'vc_tipo', 'vc_area','link_reuniao' ,'vc_pataforma','agenda_id', 'vc_nota', 'it_termo','it_envDocs','vc_caminho_documento'
    ];
}
