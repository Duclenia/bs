<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use App\Notifications\ProcessoNotificacao;

class Processo extends Model
{
    use Notifiable;
    
    protected $table = 'processo';
    
    
    public function areaprocessual()
    {
        return $this->belongsTo(AreaProcessual::class, 'areaprocessual_id');
    }
    
    
    public function orgaoJudiciario()
    {
        return $this->belongsTo(OrgaoJudiciario::class, 'orgaojudiciario_id');
    }

    public function tribunal()
    {
        return $this->belongsTo(Tribunal::class, 'tribunal_id');
    }

    public function tipoProcesso()
    {
        return $this->belongsTo(TipoProcesso::class, 'tipoprocesso_id');
    }
    
    public function seccao()
    {
        return $this->belongsTo(Seccao::class, 'seccao_id');
    }
    
    public function estadoprocesso()
    {
        return $this->belongsTo(EstadoProcesso::class, 'estado');
    }
    
    
    public function juiz()
    {
        return $this->belongsTo(Juiz::class, 'juiz_id');
    }
    
    public function cliente()
   {
        return $this->belongsTo(Cliente::class);
   }
    
    public function posicaocliente()
    {
        return $this->belongsTo(IntervenienteDesignacao::class, 'client_position');
    }
    
    public function membros()
    {
        return $this->belongsToMany(Admin::class, 'processo_membro', 'processo_id', 'membro');
    }
    
    public function autos()
    {
        return $this->hasMany(Auto::class);
    }
    
    public function comentarios()
    {
        return $this->hasMany(Comentario::class,'processo_id','id');
    }
    
    public function notificacao($membros, $processo)
    {
        
        $membros->map(function($membro){
                
                return $membro->utilizador;
                
            })->each->notify(new ProcessoNotificacao($processo));
        
    }
}
