<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstadoProcesso extends Model
{
    protected $table = 'estadoprocesso';
    
    public $timestamps = false;
    
    public function areasprocessuais()
    {
        return $this->belongsToMany(AreaProcessual::class, 'estadoprocesso_areaprocessual', 'estadoprocesso_id', 'areaprocessual_id');

    }
}
