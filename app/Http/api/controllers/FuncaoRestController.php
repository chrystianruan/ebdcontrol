<?php

namespace App\Http\api\controllers;

use App\Http\Controllers\Controller;
use App\Http\Repositories\FuncaoRepository;
use Illuminate\Database\Eloquent\Collection;

class FuncaoRestController extends Controller
{
    private $funcaoRepository;
    public function __construct(FuncaoRepository $funcaoRepository) {
        $this->funcaoRepository = $funcaoRepository;
    }

    public function getFuncaos() : ?Collection {
        try {
            return $this->funcaoRepository->findAll();
        } catch (\Exception $e) {
            return abort(500);
        }

    }
}
