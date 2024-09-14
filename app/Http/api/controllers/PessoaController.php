<?php

namespace App\Http\api\controllers;

use App\Http\Controllers\Controller;
use App\Http\Repositories\PessoaRepository;
use Illuminate\Database\Eloquent\Collection;

class PessoaController extends Controller
{
    private $pessoaRepository;
    public function __construct(PessoaRepository $pessoaRepository)
    {
        $this->pessoaRepository = $pessoaRepository;
    }
    public function getPessoasBySalaWithPresencas(int $salaId) :? Collection {
        return $this->pessoaRepository->findBySalaIdAndSituacaoWithPresenca($salaId);
    }
}
