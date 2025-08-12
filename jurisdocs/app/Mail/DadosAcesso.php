<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DadosAcesso extends Mailable
{
    use Queueable, SerializesModels;
    
    public $subject = 'Dados de Acesso ao JurisDocs';
    
    public $cliente;
    public $email;
    public $password;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($cliente,$email, $password)
    {   
        $this->cliente = $cliente;
        $this->email = $email;
        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.dados_conta');
    }
}
