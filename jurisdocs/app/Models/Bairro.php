<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bairro extends Model
{
    protected $table = 'bairro';
    
    public $timestamps = false;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 'nome'];
    
    
    public function municipios()
    {
        return $this->belongsToMany(Municipio::class, 'bairro_municipio', 'bairro_id', 'municipio_id');
    }
}
