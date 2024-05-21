<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PessoaCadastradaMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $pessoaNome;
    protected $congregacaoNome;
    public function __construct(string $pessoaNome, string $congregacaoNome)
    {
        $this->pessoaNome = $pessoaNome;
        $this->congregacaoNome = $congregacaoNome;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.pessoaCadastrada', [
            'pessoaNome' => $this->pessoaNome,
            'congregacaoNome' => $this->congregacaoNome,
            'subject' => 'Pessoa cadastrada'
        ])->subject('Pessoa cadastrada');
    }
}
