<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IntervenienteDesignacao extends Model
{
    protected $table= 'intervdesignacao';
    
    public $timestamps = false;
    
    protected $fillable = [
        'designacao', 'activo'
    ];
    
    public function areasprocessuais()
    {
        return $this->belongsToMany(AreaProcessual::class, 'areaprocessual_intervdesignacao', 'intervdesignacao_id', 'areaprocessual_id');

    }
}
