<?php

namespace App\Http\Requests;

use App\Models\Formation;
use App\Models\Publico;
use App\Models\Sala;
use App\Models\Uf;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePessoaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules() : array
    {
        $ufs = Uf::all();
        $formations = Formation::all();
        $publicos = Publico::all();

        return [
            'nome' => ['required'],
            'sexo' => ['required', 'integer', 'min: 1', 'max: 2'],
            'data_nasc' => ['required'],
            'id_uf' => ['required', 'integer', 'min: 1', 'max:'.$ufs->count()],
            'telefone' => ['nullable', 'integer', 'min:11111111111', 'max:99999999999', 'unique:pessoas,telefone,'.request('id')],
            'telefone_responsavel' => ['nullable', 'integer', 'min:11111111111', 'max:99999999999'],
            'id_formation' => ['required', 'integer', 'min: 1', 'max:'.$formations->count()],
            'list_salas' => ['required'],
            'interesse' => ['required'],
            'frequencia_ebd' => ['integer', 'min: 1', 'max: 3'],
            'situacao' => ['integer', 'min:1', 'max:2'],
            'curso_teo' => ['integer', 'min: 1', 'max: 2'],
            'prof_ebd' => ['integer', 'min: 1', 'max: 2'],
            'prof_comum' => ['integer', 'min: 1', 'max: 2'],
            'id_public' => ['integer', 'min: 1', 'max:'.$publicos->count()]
        ];
    }

    public function messages() : array {
        return [
            'nome.required' =>  'Nome é obrigatório.',

            'sexo.required' =>  'Sexo é obrigatório.',
            'sexo.integer' =>  'Só é aceito o sexo masculino ou feminino',
            'sexo.min' =>  'Só é aceito o sexo masculino ou feminino',
            'sexo.max' =>  'Só é aceito o sexo masculino ou feminino',
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
            'list_salas' =>  'Classe(s) é(são) obrigatória(s).',
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
            'situacao.integer' =>  'Situação escolhida não existe',
            'situacao.min' =>  'Situação escolhida não existe',
            'situacao.max' =>  'Situação escolhida não existe',
            'prof_ebd.integer' =>  'Escolha para professor de EBD escolhida não existe.',
            'prof_ebd.min' =>  'Escolha para professor de EBD escolhida não existe.',
            'prof_ebd.max' =>  'Escolha para professor de EBD escolhida não existe.',
            'prof_comum.integer' =>  'Escolha para professor comum escolhida não existe.',
            'prof_comum.min' =>  'Escolha para professor comum escolhida não existe.',
            'prof_comum.max' =>  'Escolha para professor comum escolhida não existe.',
            'id_public.integer' =>  'Público escolhido não existe.',
            'id_public.min' =>  'Público escolhido não existe.',
            'id_public.max' =>  'Público escolhido não existe.',
        ];
    }
}
