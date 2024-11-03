<?php

namespace App\Http\api\controllers;

use App\Http\Controllers\Controller;
use App\Http\Repositories\PessoaRepository;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PessoaRestController extends Controller
{
    private $pessoaRepository;
    public function __construct(PessoaRepository $pessoaRepository)
    {
        $this->pessoaRepository = $pessoaRepository;
    }
    public function getPessoasBySalaWithPresencas(int $salaId) :? Collection {
        return $this->pessoaRepository->findBySalaIdAndSituacaoWithPresenca($salaId);
    }

    public function verifyDuplicated(Request $request) : JsonResponse {
        try {
            if ($this->pessoaRepository->findByNome($request->nome, intval($request->congregacao))->count() > 0) {
                return response()->json([
                    'response' => 'Possível duplicata encontrada'
                ], 403);
            }
            return response()->json([
                'response' => 'Nenhuma duplicata encontrada'
            ]);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'response' => 'Erro ao tentar verificar duplicidade'
            ], 500);
        }

    }
}
