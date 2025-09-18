<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgendamentoConsulta extends Model
{
     protected $fillable = [

        'vc_tipo', 'vc_area','link_reuniao','meeting_id','join_url','start_url' ,'vc_pataforma','agenda_id', 'vc_nota', 'it_termo','it_envDocs','vc_caminho_documento'
    ];
}
