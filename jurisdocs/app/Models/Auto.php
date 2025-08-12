<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Auto extends Model
{
   protected $table = 'auto';
   
   protected $fillable = [ 'descricao', 'anexo', 'processo_id', 'autor'];
   
   
   public function processo()
   {
        return $this->belongsTo(Processo::class);
   }
}
