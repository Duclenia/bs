<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoProcesso extends Model
{
    protected $table = 'tipoprocesso';
    
    public $timestamps = false;
    
    protected $fillable = [
        'designacao', 'activo'
    ];
    
    
    public function areasprocessuais()
    {
        return $this->belongsToMany(AreaProcessual::class, 'areaprocessual_tipoprocesso', 'tipoprocesso_id', 'areaprocessual_id');

    }
}
