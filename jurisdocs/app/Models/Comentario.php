<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comentario extends Model
{
    protected $table = 'comentario';
    
    public function processo()
    {
        return $this->belongsTo(Processo::class);
    }
}
