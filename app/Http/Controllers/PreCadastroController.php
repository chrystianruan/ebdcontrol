<?php

namespace App\Http\Controllers;

use App\Http\Repositories\PessoaRepository;
use App\Http\Repositories\SalaRepository;
use App\Http\Requests\StorePessoaRequest;
use App\Http\Requests\UpdatePessoaRequest;
use App\Http\Services\PessoaService;
use App\Models\Formation;
use App\Models\Funcao;
use App\Models\PreCadastro;
use App\Models\Publico;
use App\Models\Sala;
use App\Models\Uf;
use http\Env\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class PreCadastroController extends Controller
{

    private SalaRepository $salaRepository;
    private PessoaRepository $pessoaRepository;
    private PessoaService $pessoaService;
    public function __construct(SalaRepository $salaRepository, PessoaRepository $pessoaRepository, PessoaService $pessoaService) {
        $this->salaRepository = $salaRepository;
        $this->pessoaRepository = $pessoaRepository;
        $this->pessoaService = $pessoaService;
    }
    public function store(StorePessoaRequest $request) : ?RedirectResponse{
        try {
            $classeIdRequest = intval($request->classe);
            $congregacaoIdRequest = intval($request->congregacao);
            if ($this->pessoaRepository->findByCongregacao($congregacaoIdRequest)->count() < 1) {
                return $this->pessoaService->store($request);
            }
            $pessoa = new PreCadastro();
            if ($this->pessoaRepository->findByNome($request->nome, auth()->user()->congregacao_id)->count() > 0) {
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

    public function edit(int $id) : ?View {
        $pessoa = PreCadastro::findOrFail($id);
        $salas = Sala::where('id', '>', 2)
            ->where('congregacao_id', '=', auth()->user()->congregacao_id)
            ->orderBy('nome')->get();
        $ufs = Uf::orderBy("nome")->get();
        $functions = Funcao::all();
        $publicos = Publico::all();
        $formations = Formation::all();


        return view('/admin/edit/pre-cadastro', compact(['pessoa', 'salas', 'ufs', 'functions', 'publicos', 'formations']));
    }

    public function update(UpdatePessoaRequest $request, int $id) : ?RedirectResponse {
        $pessoa = PreCadastro::findOrFail($id);
        if ($this->pessoaRepository->findByNome($request->nome, auth()->user()->congregacao_id)->count() > 0) {
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
        $pessoa->congregacao = auth()->user()->congregacao_id;
        $pessoa->classe = intval($request->list_salas);
        $pessoa->situacao = 1;
        $pessoa->interesse = $request->interesse;
        $pessoa->frequencia_ebd = $request->frequencia_ebd;
        $pessoa->curso_teo = $request->curso_teo;
        $pessoa->prof_ebd = $request->prof_ebd;
        $pessoa->prof_comum = $request->prof_comum;
        $pessoa->id_public = $request->id_public;
        $pessoa->save();

        return redirect('/admin/filtro/pre-cadastros')->with('msg', 'Pessoa foi atualizada com sucesso');
    }

    public function list(Request $request) : ?View {
        $pessoas = PreCadastro::where('congregacao', '=' ,auth()->user()->congregacao_id);
        if ($request->classe) {
            $pessoas = $pessoas->where('classe', '=', $request->classe);
        }
        if ($request->nome) {
            $pessoas = $pessoas->where([['nome', 'like', '%' . $request->nome . '%']]);
        }
        $pessoas = $pessoas->get();
        $salas = $this->salaRepository->findSalasByCongregacaoId(auth()->user()->congregacao_id);

        $nome = $request->nome;
        $classe = $request->classe;

        return view('/admin/filtro/pre-cadastro', compact(['pessoas', 'salas', 'nome', 'classe']));
    }

    public function approve(int $id) : ?RedirectResponse {
        try {
            $preCadastro = PreCadastro::findOrFail($id);

            $this->pessoaService->store($preCadastro);

            $preCadastro->delete();

            return redirect()->back()->with('msg', 'Pessoa cadastrada com sucesso!');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('msg2', 'Erro desconhecido ao aprovar pré-cadastro. Contate o administrador.');
        }
    }
}
