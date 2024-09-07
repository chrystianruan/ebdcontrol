<?php

namespace App\Http\api\controllers;

use App\Http\Controllers\Controller;
use App\Http\Repositories\SalaRepository;
use App\Models\Sala;
use Illuminate\Database\Eloquent\Collection;

class SalaController extends Controller
{
    private $salaRepository;
    public function __construct(SalaRepository $salaRepository) {
        $this->salaRepository = $salaRepository;
    }
    public function getSalasByCongregacao($congregacaoId) : ?Collection {
        return $this->salaRepository->findSalasByCongregacaoId(base64_decode($congregacaoId));
    }
}
