<?php

namespace App\Http\Services;

use App\Http\Controllers\Controller;
use App\Http\Enums\FuncaoEnum;
use App\Http\Enums\TipoCadastroPessoaEnum;
use App\Http\Repositories\PessoaSalaRepository;
use App\Http\Requests\StorePessoaRequest;
use App\Http\Requests\UpdatePessoaRequest;
use App\Models\Formation;
use App\Models\LinkCadastroGeral;
use App\Models\Pessoa;
use App\Models\PessoaSala;
use App\Models\Publico;
use App\Models\Sala;
use App\Models\Uf;
use Dompdf\Exception;
use FontLib\TrueType\Collection;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Enum;

class PessoaService
{
    use ValidatesRequests;
    private $linkCadastroGeral;
    private $pessoaSalaRepository;
    public function __construct(LinkCadastroGeral $linkCadastroGeral, PessoaSalaRepository $pessoaSalaRepository) {
        $this->linkCadastroGeral = $linkCadastroGeral;
        $this->pessoaSalaRepository = $pessoaSalaRepository;
    }
    public function liberarLinkGeral(int $congregacaoId) {
        $linkExistente = $this->linkCadastroGeral->getLink($congregacaoId);
        if ($linkExistente) {
            if ($linkExistente->active == 1) {
                $linkExistente->active = 0;
                $linkExistente->save();

                return response()->json([
                    'response' => 'Link desativado com sucesso'
                ]);
            }
            $linkExistente->active = 1;
            $linkExistente->save();

            return response()->json([
                'response' => 'Link ativado com sucesso'
            ]);
        }
        $linkGeral = new LinkCadastroGeral();
        $linkGeral->congregacao_id = $congregacaoId;
        $linkGeral->active = true;
        $linkGeral->save();

        return response()->json([
            'response' => 'Link liberado com sucesso'
        ]);
    }


    public function store(StorePessoaRequest $request) {
        try {
            $classeIdRequest = intval($request->get('classe'));
            $congregacaoIdRequest = intval($request->get('congregacao'));
            $hash = hash('sha256', mt_rand());
            $pessoa = new Pessoa;
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
            $pessoa->id_sala = ["$classeIdRequest"];
            $pessoa->id_funcao = 1;
            $pessoa->congregacao_id = $congregacaoIdRequest;
            $pessoa->situacao = 1;
            $pessoa->interesse = $request->interesse;
            $pessoa->frequencia_ebd = $request->frequencia_ebd;
            $pessoa->curso_teo = $request->curso_teo;
            $pessoa->prof_ebd = $request->prof_ebd;
            $pessoa->prof_comum = $request->prof_comum;
            $pessoa->id_public = $request->id_public;
            $pessoa->hash = $hash;
            $pessoa->save();

            $pessoaCadastrada = Pessoa::where('hash', $hash)->first();

            $this->storePessoaInSala($pessoa->id, $classeIdRequest);

            $pessoaCadastrada->hash = null;
            $pessoaCadastrada->save();

            return redirect()->back()->with('msg', 'Pessoa cadastrada com sucesso');
        } catch (\Exception $exception) {
            Log::info($exception->getMessage());
            throw $exception;
        }
    }

    public function update(UpdatePessoaRequest $request){
        try {
            $pessoa = Pessoa::findOrFail($request -> id);
            $pessoa-> nome = $request->nome;
            $pessoa-> sexo = $request->sexo;
            if ($request->filhos == 2 && $request->sexo == 1) {
                $pessoa->paternidade_maternidade = "Pai";
            }
            elseif ($request->filhos == 2 && $request->sexo == 2) {
                $pessoa->paternidade_maternidade = "Mãe";
            } else {
                $pessoa->paternidade_maternidade = null;
            }
            $pessoa-> data_nasc = $request->data_nasc;
            $pessoa-> responsavel = $request->responsavel;
            $pessoa->telefone_responsavel = $request->telefone_responsavel;
            $pessoa-> ocupacao = $request->ocupacao;
            $pessoa-> cidade = $request->cidade;
            $pessoa-> id_uf = $request->id_uf;
            $pessoa-> telefone = $request->telefone;
            $pessoa-> id_formation = $request->id_formation;
            $pessoa-> cursos = $request->cursos;
            $pessoa-> id_funcao = $request->id_funcao;
            $pessoa-> situacao = $request->situacao;
            $pessoa-> interesse = $request->interesse;
            $pessoa-> frequencia_ebd = $request->frequencia_ebd;
            $pessoa-> curso_teo = $request->curso_teo;
            $pessoa-> prof_ebd = $request->prof_ebd;
            $pessoa-> prof_comum = $request->prof_comum;
            $pessoa-> id_public = $request->id_public;
            $pessoa-> congregacao_id = auth()->user()->congregacao_id;
            $pessoa -> save();

            $this->updatePessoaInSala($pessoa->id, json_decode($request->list_salas, true));

            return redirect('/admin/filtro/pessoa')->with('msg', 'Pessoa foi atualizada com sucesso');
        } catch (\Exception $exception) {
            Log::info($exception->getMessage());
            throw $exception;
        }
    }


    private function storePessoaInSala(int $pessoaId, int $salaId) : void {
        try {
            $pessoaSala = new PessoaSala();
            $pessoaSala->pessoa_id = $pessoaId;
            $pessoaSala->sala_id = $salaId;
            $pessoaSala->funcao_id = FuncaoEnum::ALUNO;
            $pessoaSala->active = 1;
            $pessoaSala->save();

        } catch (\Exception $e) {
            throw $e;
        }
    }

    private function updatePessoaInSala(int $pessoaId, array $salas) : void {
        try {
            $this->clearPessoaSala($pessoaId);
            foreach ($salas as $sala) {
                $pessoaSala = new PessoaSala();
                $pessoaSala->pessoa_id = $pessoaId;
                $pessoaSala->sala_id = $sala['sala_id'];
                $pessoaSala->funcao_id = $sala['funcao_id'];
                $pessoaSala->active = true;
                $pessoaSala->save();
             }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    private function clearPessoaSala(int $pessoaId) : void {
        try {
            $salasPessoa = $this->pessoaSalaRepository->getSalasOfPessoa($pessoaId);
            foreach ($salasPessoa as $sp) {
                $pessoaSala = PessoaSala::findOrFail($sp->id);
                $pessoaSala->delete();
            }
        } catch (\Exception $exception) {
            throw $exception;
        }
    }
}
