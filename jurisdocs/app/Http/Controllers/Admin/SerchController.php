<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Pais;
use App\Models\Category;
use App\Models\Provincia;
use App\Models\Municipio;
use App\Model\Series;
use App\Models\AreaProcessual;
use App\Models\TribunalAreaprocessualSeccao;
use App\Models\CrimEnquad;
use App\Models\CrimeSubEnquad;
use App\Models\Crime;
use App\Models\OrgaoJudiciario;
use App\Models\EstadoProcesso;
use DB;



class SerchController extends Controller
{
    
    private $areaProcessual;
    private $taps;
    private $provincia;
    private $municipio;
    private $crimEnquad;
    private $tipoCrime;
    private $estadoprocesso;
    
    public function __construct(AreaProcessual $areaprocessual, TribunalAreaprocessualSeccao $taps, Provincia $provincia, Municipio $municipio, Crime $tipoCrime,
                                 CrimEnquad $crimEnquad,EstadoProcesso $estadoprocesso)
    {
        
        $this->areaProcessual = $areaprocessual;
        $this->taps = $taps;
        $this->provincia = $provincia;
        $this->municipio = $municipio;
        $this->crimEnquad = $crimEnquad;
        $this->tipoCrime = $tipoCrime;
        
    }
    
    
     public function getCountry(Request $request)
    {

        $search = $request->get('search');
        $id = $request->get('id');

        $data = Pais::when($id, function ($query, $id) {
            $query->where('id', $id);
        })
        ->where('nome', 'like', '%' . $search . '%')
        ->get();

        return response()->json($data->toArray());

    }

    
    public function getState(Request $request)
    {

        $search = $request->get('search');
        $id = $request->get('id');

        $data = Provincia::when($id, function ($query, $id) {
            $query->where('pais_id', $id);
        })
        ->where('nome', 'like', '%' . $search . '%')
        ->get();
        
        return response()->json($data->toArray());
    }

    
    public function getCity(Request $request)
    {

        $search = $request->get('search');
        
        $id = $request->get('id');

        $data = Municipio::when($id, function ($query, $id) {
            $query->where('provincia_id', $id);
        })
        ->where('nome', 'like', '%' . $search . '%')
        ->get();   
        return response()->json($data->toArray());

    }
    
    public function getCategory(Request $request)
    {

        $search = $request->get('search');
        $id = $request->get('id');

        $data = Category::when($id, function ($query, $id) {
            $query->where('id', $id);
        })
        ->where('name', 'like', '%' . $search . '%')
        ->get();
       
        return response()->json($data->toArray());

    }
    

    public function getSeries(Request $request)
    {

        $search = $request->get('search');
        $id = $request->get('id');

        $data = Series::when($id, function ($query, $id) {
            $query->where('id', $id);
        })
        ->where('series_name', 'like', '%' . $search . '%')
        ->get();
       
        return response()->json($data->toArray());

    }
    
    
    public function getAreaprocessual(Request $request)
    {
        $search = $request->get('search');
        $id = $request->get('id');

        $data = $this->areaProcessual->when($id, function ($query, $id) {
            $query->where('id', $id);
        })
        ->where('designacao', 'like', '%' . $search . '%')
        ->get();

        return response()->json($data->toArray());
    }
    
    public function getTipoProcesso(Request $request)
    {
        $id = $request->get('id');
        
        $tiposProcessos = $this->areaProcessual->tiposProcessos($id);
                
        return response()->json($tiposProcessos);
    }
    
    public function getEstadoProcesso(Request $request)
    {
        $id = $request->get('id');
        
        $areaprocessual = $this->areaProcessual->with('estadosprocesso')->findOrFail($id);
        
        $estadosprocesso = $areaprocessual->estadosprocesso;
        
        return response()->json($estadosprocesso);
          
    }
    
    public function getOrgaoJudiciario(Request $request)
    {
        $search = $request->get('search');
        $id = $request->get('id');

        $data = OrgaoJudiciario::when($id, function ($query, $id) {
            $query->where('id', $id);
        })
        ->where('designacao', 'like', '%' . $search . '%')
        ->get();

        return response()->json($data->toArray());
    }


    public function getCrimEnquadramento(Request $request)
    {
        $search = $request->get('search');
        $id = $request->get('id');

        $data = $this->crimEnquad->when($id, function ($query, $id) {
            $query->where('id', $id);
        })
        ->where('designacao', 'like', '%' . $search . '%')
        ->get();

        return response()->json($data->toArray());
        
    }
    
    public function getCrimSubEnquadramento(Request $request)
    {
        $search = $request->get('search');
        $id = $request->get('id');

        $data = CrimeSubEnquad::when($id, function ($query, $id) {
            $query->where('idEnq', $id);
        })
        ->where('designacao', 'like', '%' . $search . '%')
        ->get();
        
        return response()->json($data->toArray());
        
    }


    public function getTipoCrime(Request $request)
    {
        $search = $request->get('search');
        $id = $request->get('id');

        $data = $this->tipoCrime->when($id, function ($query, $id) {
            $query->where('id', $id);
        })
        ->where('designacao', 'like', '%' . $search . '%')
        ->get();

        return response()->json($data->toArray());
    }
    
    
    public function getTribunais(Request $request)
    {
        $id = $request->id;
        
        $tribunais = $this->areaProcessual->getTribunais($id);

        return response()->json($tribunais);
    }
    
    /**
     * 
     * @param int $areaprocessual
     * @param int $tribunal
     * @return type
     */
    public function getSeccoes(Request $request)
    {
        $areaprocessual = $request->areaprocessual;
        $tribunal = $request->tribunal;
        
        $seccoes = $this->taps->getSeccao($areaprocessual, $tribunal);
        
        return response()->json($seccoes);
    }
    
    /**
     * 
     * @param int $areaprocessual
     * @param int $tribunal
     * @param int $seccao
     * @return type
     */
    public function getJuizes(Request $request)
    {
        $areaprocessual = $request->areaprocessual;
        $tribunal = $request->tribunal;
        $seccao = $request->seccao;
        
        $juizes = $this->taps->getJuiz($areaprocessual, $tribunal, $seccao);
        
        return response()->json($juizes);
    }
    
    /**
     * 
     * @param int $id_provincia
     * @return type
     */
    public function getMunicipios($id_provincia)
    {
        
        $provincia = $this->provincia->findOrFail($id_provincia);
    
        $municipios = $provincia->municipios()->getQuery()->get(['nome', 'id']);

        return response()->json($municipios);
    }
    
    /**
     * 
     * @param Request $request
     * @return type
     */
    public function getBairros(Request $request)
    {
        $cod_municipio = $request->cod_municipio;
        
        $bairros = $this->municipio->listarBairrosMunicipio($cod_municipio);

        return response()->json($bairros);
    }
    
    /**
     * 
     * @param int $id_areaProcessual
     * @return type
     */
    public function getDesignacaoInterveniente(Request $request)
    {
        $id = $request->id;
        
        $intervDesignacao = $this->areaProcessual->intervDesignacao($id);

        return response()->json($intervDesignacao);
    }
}
