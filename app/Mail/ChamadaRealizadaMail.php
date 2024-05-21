<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ChamadaRealizadaMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $classeNome;
    protected $congregacaoNome;
    public function __construct(string $classeNome, string $congregacaoNome)
    {
        $this->classeNome = $classeNome;
        $this->congregacaoNome = $congregacaoNome;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.chamadaRealizada', [
            'classeNome' => $this->classeNome,
            'congregacaoNome' => $this->congregacaoNome,
        ])->subject('Chamada Realizada');
    }
}
