<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MeetingCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $agendamento;

    public function __construct($agendamento)
    {
        $this->agendamento = $agendamento;
    }

    public function build()
    {
        return $this->subject('Reunião Agendada - ' . $this->agendamento->titulo)
                    ->view('emails.reuniao-agendada');
    }
}
