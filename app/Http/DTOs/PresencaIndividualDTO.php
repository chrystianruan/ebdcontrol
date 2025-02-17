<?php

namespace App\Http\DTOs;

class PresencaIndividualDTO
{
    private int $pessoaId;
    private int $funcaoId;
    private bool $presenca;

    public function __construct(int $pessoaId, int $funcaoId, bool $presenca)
    {
        $this->pessoaId = $pessoaId;
        $this->funcaoId = $funcaoId;
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
    public function getFuncaoId() : int
    {
        return $this->funcaoId;
    }
    public function setFuncaoId($funcaoId) : void
    {
        $this->funcaoId = $funcaoId;
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
