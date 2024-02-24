<?php

namespace App\Http\Services;

use App\Http\Controllers\Controller;
use App\Http\Enums\TipoCadastroPessoaEnum;
use App\Models\Formation;
use App\Models\LinkCadastroGeral;
use App\Models\Pessoa;
use App\Models\Publico;
use App\Models\Sala;
use App\Models\Uf;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;

class PessoaService
{
    use ValidatesRequests;
    private $linkCadastroGeral;
    public function __construct(LinkCadastroGeral $linkCadastroGeral) {
        $this->linkCadastroGeral = $linkCadastroGeral;
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


    public function store(Request $request, int $classeIdRequest, int $congregacaoIdRequest) {
        $publicos = Publico::all();
        $ufs = Uf::all();
        $formations = Formation::all();

        $lastSala = Sala::where('congregacao_id', '=', $congregacaoIdRequest)
            ->orderBy('id', 'desc')
            ->first();

        $this->validate($request, [
            'nome' => ['required'],
            'sexo' => ['required', 'integer', 'min: 1', 'max: 2'],
            'filhos' => ['required', 'integer', 'min: 1', 'max: 2'],
            'data_nasc' => ['required'],
            'id_uf' => ['required', 'integer', 'min: 1', 'max:'.$ufs->count()],
            'telefone' => ['nullable', 'integer', 'min:11111111111', 'max:99999999999', 'unique:pessoas,telefone'],
            'telefone_responsavel' => ['nullable', 'integer', 'min:11111111111', 'max:99999999999'],
            'id_formation' => ['required', 'integer', 'min: 1', 'max:'.$formations->count()],
            'classe' => ['required', 'max:'.$lastSala->id],
            'interesse' => ['required', 'integer', 'min: 1', 'max: 3'],
            'frequencia_ebd' => ['integer', 'min: 1', 'max: 3'],
            'curso_teo' => ['integer', 'min: 1', 'max: 2'],
            'prof_ebd' => ['integer', 'min: 1', 'max: 2'],
            'prof_comum' => ['integer', 'min: 1', 'max: 2'],
            'id_public' => ['integer', 'min: 1', 'max:'.$publicos->count()],
        ], [
            'nome.required' =>  'Nome é obrigatório.',

            'sexo.required' =>  'Sexo é obrigatório.',
            'sexo.integer' =>  'Só é aceito o sexo masculino ou feminino',
            'sexo.min' =>  'Só é aceito o sexo masculino ou feminino',
            'sexo.max' =>  'Só é aceito o sexo masculino ou feminino',

            'filhos.required' =>  'Campo de filhos é obrigatório.',
            'filhos.integer' =>  'Só é aceito ter ou não filhos',
            'filhos.min' =>  'Só é aceito ter ou não filhos',
            'filhos.max' =>  'Só é aceito ter ou não filhos',

            'data_nasc.required' =>  'Data de nascimento é obrigatória.',

            'id_uf.required' =>  'UF é obrigatória.',
            'id_uf.integer' =>  'UF escolhida não existe.',
            'id_uf.min' =>  'UF escolhida não existe.',
            'id_uf.max' =>  'UF escolhida não existe.',

            'telefone.integer' =>  'O telefone precisa de 11 dígitos: DDD + número',
            'telefone.min' =>  'O telefone precisa de 11 dígitos: DDD + número',
            'telefone.max' =>  'O telefone precisa de 11 dígitos: DDD + número',
            'telefone.unique' =>  'O telefone já existe.',

            'telefone_responsavel.integer' =>  'O telefone precisa de 11 dígitos: DDD + número',
            'telefone_responsavel.min' =>  'O telefone precisa de 11 dígitos: DDD + número',
            'telefone_responsavel.max' =>  'O telefone precisa de 11 dígitos: DDD + número',

            'id_formation.required' =>  'Formação é obrigatória.',
            'id_formation.integer' =>  'Formação escolhida não existe.',
            'id_formation.min' =>  'Formação escolhida não existe.',
            'id_formation.max' =>  'Formação escolhida não existe.',

            'classe.required' =>  'Classe é obrigatória.',
            'classe.max' =>  'Pessoa só pode ser cadastrada em uma classe',

            'interesse.required' =>  'Interesse é obrigatório.',
            'interesse.integer' =>  'Interesse escolhido não existe.',
            'interesse.min' =>  'Interesse escolhido não existe.',
            'interesse.max' =>  'Interesse escolhido não existe.',

            'frequencia_ebd.integer' =>  'Frequência escolhida não existe.',
            'frequencia_ebd.min' =>  'Frequência escolhida não existe.',
            'frequencia_ebd.max' =>  'Frequência escolhida não existe.',

            'curso_teo.integer' =>  'Valor inválido para curso de Teologia',
            'curso_teo.min' =>  'Valor inválido para curso de Teologia',
            'curso_teo.max' =>  'Valor inválido para curso de Teologia',

            'prof_ebd.integer' =>  'Escolha para professor de EBD escolhida não existe.',
            'prof_ebd.min' =>  'Escolha para professor de EBD escolhida não existe.',
            'prof_ebd.max' =>  'Escolha para professor de EBD escolhida não existe.',

            'prof_comum.integer' =>  'Escolha para professor comum escolhida não existe.',
            'prof_comum.min' =>  'Escolha para professor comum escolhida não existe.',
            'prof_comum.max' =>  'Escolha para professor comum escolhida não existe.',

            'id_public.integer' =>  'Público escolhido não existe.',
            'id_public.min' =>  'Público escolhido não existe.',
            'id_public.max' =>  'Público escolhido não existe.',

        ]);

        $pessoa = new Pessoa;
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
        $pessoa->save();
        return redirect()->back()->with('msg', 'Pessoa cadastrada com sucesso');
    }
}
