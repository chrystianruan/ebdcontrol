<?php

namespace App\Http\api\controllers;

use App\Http\Controllers\Controller;
use App\Http\Repositories\PessoaRepository;
use App\Http\Services\PessoaService;
use App\Models\PreCadastro;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PreCadastroRestController extends Controller
{
    private PessoaService $pessoaService;
    private PessoaRepository $pessoaRepository;

    public function __construct(PessoaService $pessoaService, PessoaRepository $pessoaRepository)
    {
        $this->pessoaService = $pessoaService;
        $this->pessoaRepository = $pessoaRepository;
    }

    public function getList(Request $request) : JsonResponse
    {
        try {
            $pessoas = PreCadastro::where('congregacao', '=', decryptId($request->congregacao_id));
            if ($request->classe_pre_register) {
                $pessoas = $pessoas->where('classe', '=', $request->classe_pre_register);
            }
            if ($request->nome_pre_register) {
                $pessoas = $pessoas->where([['nome', 'like', '%' . $request->nome_pre_register . '%']]);
            }
            $pessoas = $pessoas->with('sala')
                ->paginate(5)
                ->withQueryString();

            return response()->json($pessoas);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }

    }

    public function getPreRegister(Request $request) : JsonResponse
    {
        try {
            $pessoas = PreCadastro::where('congregacao', '=', decryptId($request->congregacao_id));
            if ($request->classe_pre_register) {
                $pessoas = $pessoas->where('classe', '=', $request->classe_pre_register);
            }
            if ($request->nome_pre_register) {
                $pessoas = $pessoas->where([['nome', 'like', '%' . $request->nome_pre_register . '%']]);
            }
            $pessoas = $pessoas->with('sala')
                ->paginate(5)
                ->withQueryString();

            return response()->json($pessoas);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function edit(int $id) : ?JsonResponse {
        $pessoa = PreCadastro::findOrFail($id);

        return response()->json($pessoa);
    }


    public function approve(Request $request) : JsonResponse {
        try {
            $ids = $request->input('ids', []);

            if (empty($ids)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nenhum ID informado'
                ], 400);
            }

            $aprovados = 0;
            $erros = [];

            foreach ($ids as $id) {
                try {
                    $preCadastro = PreCadastro::findOrFail($id);
                    $this->pessoaService->store($preCadastro);
                    $preCadastro->delete();
                    $aprovados++;
                } catch (\Exception $e) {
                    $erros[] = "ID {$id}: {$e->getMessage()}";
                }
            }

            return response()->json([
                'success' => true,
                'message' => "{$aprovados} pré-cadastro(s) aprovado(s) com sucesso!",
                'aprovados' => $aprovados,
                'erros' => $erros
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro desconhecido ao aprovar pré-cadastro(s). Contate o administrador.'
            ], 500);
        }
    }

    public function destroy(Request $request) : JsonResponse {
        try {
            $ids = $request->input('ids', []);

            if (empty($ids)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nenhum ID informado'
                ], 400);
            }

            $deletados = PreCadastro::whereIn('id', $ids)->delete();

            return response()->json([
                'success' => true,
                'message' => "{$deletados} pré-cadastro(s) deletado(s) com sucesso!",
                'deletados' => $deletados
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro desconhecido ao deletar pré-cadastro(s). Contate o administrador.'
            ], 500);
        }
    }

    public function update(Request $request, int $id) : JsonResponse {
        try {
            $pessoa = PreCadastro::findOrFail($id);
            $congregacaoId = decryptIdToInt($request->congregacao);
            if ($this->pessoaRepository->findByNome($request->nome,$congregacaoId) ->count() > 0) {
                $pessoa->duplicata = true;
            }
            $pessoa->nome = $request->nome;
            $pessoa->sexo = $request->sexo;
            if ($request->filhos == 2 && $request->sexo == 1) {
                $pessoa->paternidade_maternidade = "Pai";
            } elseif ($request->filhos == 2 && $request->sexo == 2) {
                $pessoa->paternidade_maternidade = "Mãe";
            } else {
                $pessoa->paternidade_maternidade = null;
            }
            $pessoa->responsavel = $request->responsavel;
            $pessoa->telefone_responsavel = $request->telefone_responsavel;
            $pessoa->ocupacao = $request->ocupacao;
            $pessoa->cidade = $request->cidade;
            $pessoa->data_nasc = $request->data_nasc;
            $pessoa->id_uf = $request->id_uf;
            $pessoa->telefone = $request->telefone;
            $pessoa->id_formation = $request->id_formation;
            $pessoa->cursos = $request->cursos;
            $pessoa->congregacao = $congregacaoId;
            $pessoa->classe = intval($request->sala);
            $pessoa->situacao = 1;
            $pessoa->interesse = $request->interesse;
            $pessoa->frequencia_ebd = $request->frequencia_ebd;
            $pessoa->curso_teo = $request->curso_teo;
            $pessoa->prof_ebd = $request->prof_ebd;
            $pessoa->prof_comum = $request->prof_comum;
            $pessoa->id_public = $request->id_public;
            $pessoa->save();


            return response()->json([
                'success' => true,
                'message' => 'Pré-cadastro atualizado com sucesso!',
                'preCadastro' => $pessoa
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro desconhecido ao atualizar pré-cadastro. Contate o administrador.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
