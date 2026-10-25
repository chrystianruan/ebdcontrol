<?php

namespace App\Http\api\controllers;

use App\Http\Controllers\Controller;
use App\Http\Repositories\PresencaPessoaRepository;
use App\Models\Chamada;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChamadaRestController extends Controller
{
    private $presencaPessoaRepository;

    public function __construct(PresencaPessoaRepository $presencaPessoaRepository)
    {
        $this->presencaPessoaRepository = $presencaPessoaRepository;
    }

    public function show(int $id, Request $request): JsonResponse
    {
        $chamada = Chamada::with('sala')->findOrFail($id);

        $congregacaoId = decryptIdToInt($request->congregacao_id);
        if ($chamada->sala->congregacao_id != $congregacaoId) {
            return response()->json(['error' => 'Não autorizado'], 403);
        }

        $presencas = $this->presencaPessoaRepository->findByDateAndSala(
            date('Y-m-d', strtotime($chamada->created_at)),
            $chamada->id_sala
        );

        $presencasFormatted = $presencas->map(function ($p) {
            $nomes = explode(' ', $p->pessoa->nome);
            $nomeAbreviado = $nomes[0] . ' ' . $nomes[count($nomes) - 1];

            return [
                'nome' => $nomeAbreviado,
                'aniversario' => date('d/m', strtotime($p->pessoa->data_nasc)),
                'funcao' => $p->funcao->nome,
                'presente' => $p->presente,
            ];
        });

        $assistTotal = $chamada->presentes + $chamada->visitantes;

        return response()->json([
            'chamada' => [
                'classe' => $chamada->sala->nome,
                'data' => date('d/m/Y', strtotime($chamada->created_at)),
                'matriculados' => $chamada->matriculados,
                'presentes' => $chamada->presentes,
                'visitantes' => $chamada->visitantes,
                'assist_total' => $assistTotal,
                'biblias' => $chamada->biblias,
                'revistas' => $chamada->revistas,
                'observacoes' => $chamada->observacoes,
                'perc_presentes' => $chamada->matriculados > 0 ? round(100 * $chamada->presentes / $chamada->matriculados, 1) : 0,
                'perc_biblias' => $assistTotal > 0 ? round(100 * $chamada->biblias / $assistTotal, 1) : 0,
                'perc_revistas' => $assistTotal > 0 ? round(100 * $chamada->revistas / $assistTotal, 1) : 0,
            ],
            'presencas' => $presencasFormatted,
        ]);
    }
}

