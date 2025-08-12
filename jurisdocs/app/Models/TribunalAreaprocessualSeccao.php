<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TribunalAreaprocessualSeccao extends Model
{
    protected $table = 'tribunal_areaprocessual_seccao';
    
    
    protected $fillable = [ 'trib_areaprocessual','seccao_id'];
    
    public $timestamps = false;
    
    /**
     * 
     * @param int $areaprocessual
     * @param int $tribunal
     * @return type
     */
    public function getSeccao($areaprocessual, $tribunal)
    {

        $seccoes = DB::select("SELECT s.id, s.nome
  
                            FROM seccao s, tribunal_areaprocessual_seccao taps
   
                            WHERE taps.trib_areaprocessual = (SELECT  tap.id
                                                                FROM tribunal_areaprocessual tap
                                                              
                                                                WHERE 
                                                   
                                                                tap.areaprocessual_id = ?
                                                    
                                                               AND tap.tribunal_id = ?
                   
					                )
                                            
                         AND taps.seccao_id = s.id
                         
	", [$areaprocessual, $tribunal]);

        return $seccoes;
    }
    
    
    
    /**
     * 
     * @param int $areaprocessual
     * @param int $tribunal
     * @param int $seccao
     * @return type
     */
    public function getJuiz($areaprocessual, $tribunal, $seccao)
    {

        $juizes = DB::select("SELECT j.id, j.nome
  
                            FROM juiz j
   
                            WHERE j.tribunal = (SELECT  taps.id
                                                FROM tribunal_areaprocessual_seccao taps
                                                              
                                                WHERE 
                                                   
                                                  taps.seccao_id = ?
                                                    
                                                  AND taps.trib_areaprocessual = (SELECT  tap.id
                                                                                  FROM tribunal_areaprocessual tap
                                                              
                                                                                  WHERE 
                                                   
                                                                                    tap.areaprocessual_id = ?
                                                    
                                                                                    AND tap.tribunal_id = ?
                   
					                                           )
                   
					                   )
                                             ORDER BY j.nome
                                            
                         
	", [$seccao, $areaprocessual, $tribunal]);

        return $juizes;
    }
    
    
    public function getTribunalSeccao($areaprocessual, $tribunal, $seccao)
    {

        $tribApSecao = DB::select("SELECT  taps.id
                               FROM tribunal_areaprocessual_seccao taps
                                                              
                                WHERE 
                                                   
                                   taps.seccao_id = ?
                                                    
                                   AND taps.trib_areaprocessual = (SELECT  tap.id
                                                                     FROM tribunal_areaprocessual tap
                                                              
                                                                     WHERE 
                                                   
                                                                     tap.areaprocessual_id = ?
                                                    
                                                                     AND tap.tribunal_id = ?
                   
					                           )
            
	", [$seccao, $areaprocessual, $tribunal]);

        return $tribApSecao;
    }
}
