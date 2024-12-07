<?php

namespace App\Http\DTOs;

class PresencaIndividualDadosValidacaoDTO
{
    private string $latitude;
    private string $longitude;
    private string $codigo;

    public function __construct(string $latitude, string $longitude, string $codigo)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->codigo = $codigo;
    }

    public function getLatitude() : string
    {
        return $this->latitude;
    }
    public function getLongitude() : string
    {
        return $this->longitude;
    }

    public function getCodigo() : string
    {
        return $this->codigo;
    }
    public function setLatitude(string $latitude) : void
    {
        $this->latitude = $latitude;
    }
    public function setLongitude(string $longitude) : void
    {
        $this->longitude = $longitude;
    }
    public function setCodigo(string $codigo) : void
    {
        $this->codigo = $codigo;
    }
}

