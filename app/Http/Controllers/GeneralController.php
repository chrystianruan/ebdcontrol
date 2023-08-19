<?php

namespace App\Http\Controllers;

use App\Models\Chamada;
use App\Models\Formation;
use App\Models\Funcao;
use App\Models\Pessoa;
use App\Models\Publico;
use App\Models\Sala;
use App\Models\Uf;
use Illuminate\Http\Request;

class GeneralController extends Controller
{

    public function indexPessoa() {
        $check = request('scales');
        $salas = Sala::where('id', '>', 2)->orderBy('nome')
            ->where('congregacao_id', '=', 1)
            ->get();
        $ufs = Uf::orderBy("nome")->get();
        $publicos = Publico::all();
        $formations = Formation::all();
        $dataAtual = date('d/m/Y');
        return view('/cadastro', ['dataAtual' => $dataAtual, 'salas' => $salas, 'ufs' => $ufs, 'publicos' => $publicos,
            'formations' => $formations, 'check' => $check]);
    }

    public function searchPessoaClasse(Request $request)
    {
        $nome = request('nome');
        $sexo = request('sexo');
        $niver = request('niver');
        $sala1 = request('sala');
        $id_funcao = request('id_funcao');
        $situacao = request('situacao');
        $interesse = request('interesse');
        $meses_abv = [1 => 'Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
        $salas = Sala::where('id', '>', 2)
            ->where('congregacao_id', '=', 1)
            ->orderBy('nome')
            ->get();
        $pessoas = Pessoa::select('*');

        if ($request->nome) {
            $pessoas = $pessoas->where([['nome', 'like', '%' . $request->nome . '%']]);
        }

        if ($request->sexo) {
            $pessoas = $pessoas->where('sexo', $request->sexo);
        }

        if ($request->sala) {
            $pessoas = $pessoas->whereJsonContains('id_sala', $request->sala);
        }


        $pessoas = $pessoas->where('congregacao_id', '=', 1)
            ->orderBy('nome')
            ->get();


        return view('/pessoas', ['pessoas' => $pessoas, 'nome' => $nome, 'sexo' => $sexo,
            'id_funcao' => $id_funcao, 'interesse' => $interesse, 'situacao' => $situacao,
            'meses_abv' => $meses_abv, 'niver' => $niver, 'salas' => $salas, 'sala1' => $sala1]);
    }
    public function getChamadas($initial_date, $final_date, $congregacao_id, $classe_id) {
        $chamadas = Chamada::where('id_sala', '=', $classe_id)
            ->whereBetween('created_at',  [$initial_date, $final_date])
            ->where('congregacao_id', '=', $congregacao_id)
            ->get();

        return $chamadas;
    }
    public function getListWithNameAndPresencasDuplicates($chamadas) {
        $namesAndPresencas = [];
        foreach ($chamadas as $cha) {
            $nomes = json_decode($cha->nomes, true);
            foreach ($nomes as $cn) {

                $namesAndPresencas[] = [
                    'id' => $cn['id'],
                    'nome' => $cn['nome'],
                    'data_nasc' => $cn['data_nasc'],
                    'id_funcao' => $cn['id_funcao'],
                    'presenca' => (int)$cn['presenca'],
                ];
            }
        }
        return json_encode($namesAndPresencas);
    }
    public function formatData(Request $request) {
        $dataArray = json_decode($request->data, true);
        $dataFormated = [];
        $funcoes = Funcao::all();
        foreach ($dataArray as $data) {
            foreach ($funcoes as $func) {
                if ($func->id == $data['id_funcao']) {
                    $funcao = $func->nome;
                }
            }
            $dataFormated[] = [
                'id' => $data['id'],
                'nome' => $data['nome'],
                'id_funcao' => $funcao,
                'data_nasc' => date('d/m/Y', strtotime($data['data_nasc'])),
                'presencas' => array_sum($data['presencas'])
            ];
        }

        return $dataFormated;
    }


}
