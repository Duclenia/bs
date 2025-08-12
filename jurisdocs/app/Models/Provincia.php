<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provincia extends Model
{
    protected $table = 'provincia';
    
    
    public function municipios()
    {
        return $this->hasMany(Municipio::class);
    }
    
}
