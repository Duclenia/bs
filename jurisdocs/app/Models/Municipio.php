<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Municipio extends Model {

    protected $table = 'municipio';

    public function bairros()
    {
        return $this->belongsToMany(Bairro::class, 'bairro_municipio', 'municipio_id');
    }
    
    public function provincia()
    {
        return $this->belongsTo(Provincia::class);
    }

    public function listarBairrosMunicipio($cod_municipio)
    {

        $bairros = DB::select("SELECT bairro.id, nome
            
                                 FROM bairro, bairro_municipio
                                 
                                 WHERE
                                 
                                 bairro_municipio.bairro_id = bairro.id
                                 AND bairro_municipio.municipio_id = ?
                                 
                                 ORDER BY nome", [$cod_municipio]
        );

        return $bairros;
    }

}
