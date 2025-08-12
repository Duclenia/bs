<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Despesa extends Model
{
    protected $table = 'despesa';
    
    public $timestamps = false;
    
    
    public function itensDespesa()
    {
        return $this->hasMany(ItemDespesa::class,'despesa_id','id');
    }
}
