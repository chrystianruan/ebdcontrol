<?php

namespace App\Http\api\controllers;

use App\Http\Controllers\Controller;
use App\Http\Repositories\SalaRepository;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

class SalaRestController extends Controller
{
    private $salaRepository;
    public function __construct(SalaRepository $salaRepository) {
        $this->salaRepository = $salaRepository;
    }
    public function getSalasByCongregacao(Request $request) : ?Collection {
        return $this->salaRepository->findSalasByCongregacaoId(decryptIdToInt($request->congregacao_id));
    }
}
