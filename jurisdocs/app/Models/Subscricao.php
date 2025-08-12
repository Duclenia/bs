<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscricao extends Model
{
    protected $table = 'subscricao';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 'data_inicio', 'data_termino', 'periodicidade', 'processo_registado', 'total_processo', 'plano_id'];
}
