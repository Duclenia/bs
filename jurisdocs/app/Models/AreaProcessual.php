<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AreaProcessual extends Model
{
    protected $table = 'areaprocessual';
    
    
    public function estadosprocesso()
    {
        return $this->belongsToMany(EstadoProcesso::class, 'estadoprocesso_areaprocessual', 'areaprocessual_id', 'estadoprocesso_id');

    }
    
    /**
     * 
     * @param int $areaProcessual
     * @return type
     */
    public function tiposProcessos($areaProcessual)
    {

        
//        DB::table('areaprocessual_tipoprocesso AS apt')
//                            ->join('tipoprocesso AS tp', 'apt.tipoprocesso_id', '=', 'tp.id')
//                            ->select('tp.id, tp.designacao')
//                            ->where('apt.areaprocessual_id', $areaProcessual)
//                            ->orderBy('tp.designacao', 'asc')
//                            ->get();
        
        $tiposProcessos = DB::select("SELECT tipoprocesso.id, tipoprocesso.designacao
            
                                 FROM tipoprocesso, areaprocessual_tipoprocesso
                                 
                                 WHERE
                                 
                                  areaprocessual_tipoprocesso.tipoprocesso_id = tipoprocesso.id
                                  AND areaprocessual_tipoprocesso.areaprocessual_id = ?
                                 
                                  ORDER BY designacao", [$areaProcessual]
                         );

        return $tiposProcessos;
    }
    
    
    
    /**
     * 
     * @param int $areaProcessual
     * @return type
     */
    public function getTribunais($areaProcessual)
    {

        
//        DB::table('areaprocessual_tipoprocesso AS apt')
//                            ->join('tipoprocesso AS tp', 'apt.tipoprocesso_id', '=', 'tp.id')
//                            ->select('tp.id, tp.designacao')
//                            ->where('apt.areaprocessual_id', $areaProcessual)
//                            ->orderBy('tp.designacao', 'asc')
//                            ->get();
        
        $tribunais = DB::select("SELECT t.id, t.nome
            
                                 FROM tribunal t, tribunal_areaprocessual tap
                                 
                                 WHERE
                                 
                                  tap.tribunal_id = t.id
                                  AND tap.areaprocessual_id = ?
                                 
                                  ORDER BY t.nome", [$areaProcessual]
                         );

        return $tribunais;
    }
    
    
    /**
     * 
     * @param int $areaProcessual
     * @return type
     */
    public function intervDesignacao($areaProcessual)
    {
        $intervDesignacao = DB::select("SELECT intervdesignacao.id, intervdesignacao.designacao
            
                                 FROM intervdesignacao, areaprocessual_intervdesignacao
                                 
                                 WHERE
                                 
                                  areaprocessual_intervdesignacao.intervdesignacao_id = intervdesignacao.id
                                  AND areaprocessual_intervdesignacao.areaprocessual_id = ?
                                 
                                  ORDER BY designacao", [$areaProcessual]
        );

        return $intervDesignacao;
    }
}
