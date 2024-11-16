<?php

namespace App\Http\DTOs;

class PresencaIndividualDTO
{
    private int $pessoaId;
    private string $pessoaNome;
    private int $funcaoId;
    private string $funcaoNome;
    private bool $presenca;

    public function __construct(int $pessoaId, string $pessoaNome, int $funcaoId, string $funcaoNome, bool $presenca)
    {
        $this->pessoaId = $pessoaId;
        $this->pessoaNome = $pessoaNome;
        $this->funcaoId = $funcaoId;
        $this->funcaoNome = $funcaoNome;
        $this->presenca = $presenca;
    }

    public function getPessoaId() : int
    {
        return $this->pessoaId;
    }
    public function setPessoaId($pessoaId) : void
    {
        $this->pessoaId = $pessoaId;
    }
    public function getPessoaNome() : string
    {
        return $this->pessoaNome;
    }
    public function setPessoaNome($pessoaNome) : void
    {
        $this->pessoaNome = $pessoaNome;
    }
    public function getFuncaoId() : int
    {
        return $this->funcaoId;
    }
    public function setFuncaoId($funcaoId) : void
    {
        $this->funcaoId = $funcaoId;
    }
    public function getFuncaoNome() : string
    {
        return $this->funcaoNome;
    }
    public function setFuncaoNome($funcaoNome) : void
    {
        $this->funcaoNome = $funcaoNome;
    }
    public function getPresenca() : bool
    {
        return $this->presenca;
    }
    public function setPresenca($presenca) : void
    {
        $this->presenca = $presenca;
    }

}
