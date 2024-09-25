<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePessoaRequest;
use App\Models\PreCadastro;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class PreCadastroController extends Controller
{
    public function store(StorePessoaRequest $request) : ?RedirectResponse{
        try {
            $classeIdRequest = intval($request->get('classe'));
            $congregacaoIdRequest = intval($request->get('congregacao'));
            $pessoa = new PreCadastro();
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
            $pessoa->congregacao = $congregacaoIdRequest;
            $pessoa->classe = $classeIdRequest;
            $pessoa->situacao = 1;
            $pessoa->interesse = $request->interesse;
            $pessoa->frequencia_ebd = $request->frequencia_ebd;
            $pessoa->curso_teo = $request->curso_teo;
            $pessoa->prof_ebd = $request->prof_ebd;
            $pessoa->prof_comum = $request->prof_comum;
            $pessoa->id_public = $request->id_public;
            $pessoa->hash = null;
            $pessoa->save();

            return redirect()->back()->with('msg', 'Pré-cadastro realizado com sucesso! Aguarde a aprovação pela secretaria.');

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('msg2', 'Erro desconhecido ao tentar realizar o pré-cadastro. Contate o administrador.');
        }
    }
    public function destroy(int $id) : ?RedirectResponse {
        try {
            $pessoa = PreCadastro::findOrFail($id);
            $pessoa->delete();

            return redirect()->back()->with('msg', 'Pré-cadastro deletado com sucesso!');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('msg2', 'Erro desconhecido ao deletar pré-cadastro. Contate o administrador.');
        }
    }

    public function show(int $id) : ?View {
        $pessoa = PreCadastro::findOrFail($id);
        return view('/pre-cadastros',compact($pessoa));
    }

    public function list(Request $request) : ?View {
        $pessoas = PreCadastro::where('congregacao_id', auth()->user()->congregacao_id);
        if ($request->sala_id) {
            $pessoas = $pessoas->where('sala_id', $request->sala_id);
        }
        $pessoas->get();
        return view('/pre-cadastros', compact($pessoas));
    }

    public function approve(int $id) : ?RedirectResponse {
        try {
            $pessoa = PreCadastro::findOrFail($id);

            return redirect()->back()->with('msg', 'Pessoa cadastrada com sucesso!');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('msg2', 'Erro desconhecido ao aprovar pré-cadastro. Contate o administrador.');
        }
    }
}
