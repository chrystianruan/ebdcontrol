<?php

namespace App\Http\api\controllers;

use App\Http\Controllers\Controller;
use App\Http\Enums\StatusEnum;
use App\Http\Repositories\ChamadaRepository;
use App\Http\Repositories\PessoaRepository;

use App\Models\Chamada;
use App\Models\Pessoa;
use App\Models\PessoaSala;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
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
                    'dados_presenca' => $pessoaBd->dadosPresenca(),
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

    public function show(int $id) : JsonResponse {
        try {
            $pessoa = Pessoa::select(
                'pessoas.*',
                'ufs.nome as nome_uf',
                'formations.nome as nome_formation',
                'publicos.nome as nome_publico'
            )
                ->join('ufs', 'ufs.id', '=', 'pessoas.id_uf')
                ->join('formations', 'formations.id', '=', 'pessoas.id_formation')
                ->leftJoin('publicos', 'publicos.id', '=', 'pessoas.id_public')
                ->findOrFail($id);

            $salas = PessoaSala::select(
                'pessoa_salas.id',
                DB::raw('salas.id as sala_id'),
                DB::raw('funcaos.id as funcao_id'),
                'salas.nome as sala_nome',
                'funcaos.nome as funcao_nome',
                'salas.tipo as sala_tipo'
            )
                ->where('pessoa_id', $id)
                ->join('salas', 'salas.id', '=', 'pessoa_salas.sala_id')
                ->join('funcaos', 'funcaos.id', '=', 'pessoa_salas.funcao_id')
                ->get();

            $user = $pessoa->user;

            return response()->json([
                'id' => $pessoa->id,
                'nome' => $pessoa->nome,
                'sexo' => $pessoa->sexo,
                'situacao' => $pessoa->situacao,
                'data_nasc' => $pessoa->data_nasc,
                'telefone' => $pessoa->telefone,
                'cidade' => $pessoa->cidade,
                'nome_uf' => $pessoa->nome_uf,
                'id_uf' => $pessoa->id_uf,
                'ocupacao' => $pessoa->ocupacao,
                'responsavel' => $pessoa->responsavel,
                'telefone_responsavel' => $pessoa->telefone_responsavel,
                'paternidade_maternidade' => $pessoa->paternidade_maternidade,
                'nome_formation' => $pessoa->nome_formation,
                'id_formation' => $pessoa->id_formation,
                'cursos' => $pessoa->cursos,
                'interesse' => $pessoa->interesse,
                'frequencia_ebd' => $pessoa->frequencia_ebd,
                'curso_teo' => $pessoa->curso_teo,
                'prof_ebd' => $pessoa->prof_ebd,
                'prof_comum' => $pessoa->prof_comum,
                'id_public' => $pessoa->id_public,
                'nome_publico' => $pessoa->nome_publico,
                'salas' => $salas->map(fn($s) => [
                    'id' => $s->id,
                    'sala_id' => $s->sala_id,
                    'funcao_id' => $s->funcao_id,
                    'sala_nome' => $s->sala_nome,
                    'funcao_nome' => $s->funcao_nome,
                    'sala_tipo' => $s->sala_tipo,
                ]),
                'user' => $user ? [
                    'matricula' => $user->matricula,
                    'password_temp' => $user->password_temp,
                ] : null,
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['response' => 'Erro ao buscar pessoa'], 500);
        }
    }

    public function getAniversariantes(Request $request) : JsonResponse {
        try {
            $congregacaoId = decryptIdToInt($request->congregacao_id);

            $pessoas = Pessoa::select('pessoas.*')
                ->join('pessoa_salas', 'pessoa_salas.pessoa_id', '=', 'pessoas.id')
                ->where('congregacao_id', '=', $congregacaoId)
                ->where('pessoas.situacao', StatusEnum::ATIVO->value);

            if ($request->mes) {
                $pessoas = $pessoas->whereMonth('pessoas.data_nasc', '=', $request->mes);
            } else {
                $pessoas = $pessoas->whereMonth('pessoas.data_nasc', '=', Carbon::now()->month);
            }

            if ($request->classe) {
                $pessoas = $pessoas->where('pessoa_salas.sala_id', $request->classe);
            }

            if ($request->funcao) {
                $pessoas = $pessoas->where('pessoa_salas.funcao_id', $request->funcao);
            }

            $orderBy = 'pessoas.nome';
            if ($request->orderBy) {
                $orderBy = $request->orderBy == 1 ? 'pessoas.nome' : 'day(pessoas.data_nasc)';
            }

            $pessoas = $pessoas->orderBy(DB::raw($orderBy))
                ->groupBy('pessoa_salas.pessoa_id')
                ->get();

            $result = $pessoas->map(function ($pessoa) {
                return [
                    'id' => $pessoa->id,
                    'nome' => $pessoa->nome,
                    'data_nasc' => $pessoa->data_nasc,
                    'telefone' => $pessoa->telefone,
                    'telefone_responsavel' => $pessoa->telefone_responsavel,
                    'salas' => $pessoa->salas->map(fn($sala, $key) => [
                        'nome' => $sala->nome,
                        'funcao' => $pessoa->funcoes[$key]->nome ?? null,
                        'funcao_id' => $pessoa->funcoes[$key]->id ?? null,
                    ]),
                ];
            });

            return response()->json($result);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'response' => 'Erro ao buscar aniversariantes'
            ], 500);
        }
    }
}
