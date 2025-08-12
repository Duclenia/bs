<?php
namespace App\Traits;

use \Exception;

trait Mensagem 
{


	/*
		Responsável pelo envio da Mensagem, esta é a função que deverá ser chamada
		@param $contacto @string(9xxxxxxxx) o número que receberá a mensagem.
		@param $data @array o conteúdo que desejar enviar na mensagem. 
	*/
    public function alertaDeMensagem( $contacto, $data )
    {
        $sms = 'Estimado(a) '.$data['destinatario']. $data['corpo_mensagem'];

        $this->efectuarRequisicao( $contacto, $sms );
    }
    
    public function enviarCodigoVerificacao($contacto, $codigo)
    {
        
        $sms = 'Código de verificação de telemóvel: '.$codigo;
        
        $this->efectuarRequisicao( $contacto, $sms );
    }



	
	/*
		Responsável por começar o processo da requisicao de envio, transforma o corpo de requisição em formato JSON.
	*/
    private function efectuarRequisicao( $telefone, $sms )
    {
        try
        {
            $body_request = json_encode( [
                'ApiKey'=> $this->getKeys()['key'],
                'Destino'=> [$telefone],
                'Mensagem'=>$sms,
                'CEspeciais'=>'true'
            ] );
            
            $data = $this->request( $body_request, 'POST' );
            
            $data['content'] = $sms;

            \Log::debug( $data );

            return true;
        }
        catch( Exception $ex )
        {
            \Log::debug( $ex->getMessage() );
            
            return false;
        }
        return false;
    }

 	/*
		Efectua a Requisiçaõ na API destinada
		Dispensa comentários
	*/
    private function request( $body, $method )
    {
        $curl = curl_init();
        $httpHeader = [
            "Content-Type: application/json",
        ];
        

        $opts = [
            CURLOPT_URL             => $this->getKeys()['endPoint'],
            CURLOPT_CUSTOMREQUEST   => $method,
            CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1,
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_TIMEOUT         => 30,
            CURLOPT_HTTPHEADER      => $httpHeader,
            CURLOPT_POSTFIELDS      => $body
        ];

        curl_setopt_array($curl, $opts);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_RESPONSE_CODE );

        curl_close($curl);

        if( $httpcode < 300 )
        {
            return [
                'code' =>$httpcode,
                'status'=>'success',
                'message'=>'Mensagem enviada Gera com sucesso.',
            ];
        }
        return [
            'code' =>$httpcode,
            'status'=>'failed',
            'message'=>'Não foi possível enviar a mensagem.',
            'opts'=>$opts
        ];

    }

	/*
		KeyPair [key, endPoint]
	*/
    private function getKeys()
    {
        return array(
            'key'=>"e92346a698cf40af8fd95e8fae7ae3d477491edc373148de9f502142cfc68393",
            'endPoint'=>"https://api.wesender.co.ao/envio/apikey",
        );
    }
}

