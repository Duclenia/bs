<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tribunal extends Model
{
    protected $table = 'tribunal';
    
    protected $fillable = [
        'nome', 'activo'
    ];
    
    public $timestamps = false;
    
    
    public function areasprocessuais()
    {
        return $this->belongsToMany(AreaProcessual::class, 'tribunal_areaprocessual', 'tribunal_id', 'areaprocessual_id');

    }
    
    public function processos()
    {
        return $this->hasMany(Processo::class);
    }
}
