<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seccao extends Model
{
    protected $table = 'seccao';
    
    public $timestamps = false;
    
    protected $fillable = [ 'nome','areaprocessual_id', 'activo'];
    
    
    public function tribunais()
    {
        return $this->belongsToMany(TribunalAreaprocessual::class, 'tribunal_areaprocessual_seccao', 'seccao_id', 'trib_areaprocessual');

    }
}
