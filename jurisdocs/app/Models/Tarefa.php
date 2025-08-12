<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Notifications\TarefaNotificacao;

class Tarefa extends Model
{
    protected $table = 'tarefa';
    
    public $timestamps = false;
    
    
    public function membros()
    {
        return $this->belongsToMany(Admin::class, 'tarefa_membro', 'tarefa_id', 'membro_id');
    }
    
    
    public function notificacao($membros, $tarefa)
    {
        
        $membros->map(function($membro){
                
                return $membro->utilizador;
                
            })->each->notify(new TarefaNotificacao($tarefa));
        
    }
}
