<?php

namespace App\Http\api\controllers;

use App\Http\Controllers\Controller;
use App\Http\Repositories\PessoaRepository;

use App\Models\Pessoa;
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
    public function getPessoasBySalaWithPresencas(int $salaId) :? \Illuminate\Support\Collection {
        $pessoasNotFormated = $this->pessoaRepository->findBySalaIdAndSituacaoWithPresenca($salaId);
        $pessoas = $this->formatPessoas($pessoasNotFormated);
        return $pessoas;
    }

//    public function getQuantidadePresentes(\Illuminate\Support\Collection $pessoas) : int
//    {
//        $quantidadePresencas = 0;
//        foreach ($pessoas as $pessoa) {
//            if ($pessoa->presenca) {
//                $quantidadePresencas++;
//            }
//        }
//        return $quantidadePresencas;
//    }

    public function formatPessoas(Collection $pessoas) : \Illuminate\Support\Collection
    {
        $pessoasFormat = [];
        foreach ($pessoas as $pessoa) {
            $pessoaBd = Pessoa::find($pessoa->pessoa_id);
            $p =
                (object)
                [
                    'pessoa_id' => (int) $pessoa->pessoa_id,
                    'pessoa_nome' => $pessoa->pessoa_nome,
                    'funcao_nome' => $pessoa->funcao_nome,
                    'funcao_id' => (int) $pessoa->funcao_id,
                    'presenca' => (bool) $pessoaBd->presente(),
                ];
            array_push($pessoasFormat, $p);
        }
        return collect($pessoasFormat);
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
