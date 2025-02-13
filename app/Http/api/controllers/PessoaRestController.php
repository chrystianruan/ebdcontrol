<?php

namespace App\Http\api\controllers;

use App\Http\Controllers\Controller;
use App\Http\Repositories\ChamadaRepository;
use App\Http\Repositories\PessoaRepository;

use App\Models\Chamada;
use App\Models\Pessoa;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PessoaRestController extends Controller
{
    private $pessoaRepository;
    private $chamadaRepository;
    public function __construct(PessoaRepository $pessoaRepository, ChamadaRepository $chamadaRepository)
    {
        $this->pessoaRepository = $pessoaRepository;
        $this->chamadaRepository = $chamadaRepository;
    }

    public function getDataSala(int $salaId) :? JsonResponse {
        $pessoasNotFormated = $this->pessoaRepository->findBySalaIdAndSituacaoWithPresenca($salaId);
        $pessoas = $this->formatPessoas($pessoasNotFormated);

        $chamadaToday = $this->chamadaRepository->getChamadaToday($salaId);

        return response()->json([
            'pessoas' => $pessoas,
            'matriculados' => $pessoas->count(),
            'presentes' => isset($chamadaToday) ? $chamadaToday->presentes : 0,
            'assist_total' => isset($chamadaToday) ? $chamadaToday->presentes+$chamadaToday->visitantes : 0,
         ]);
    }


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
