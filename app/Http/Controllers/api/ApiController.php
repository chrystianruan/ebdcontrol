<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Congregacao;
use App\Models\Pessoa;
use App\Models\Setor;
use Illuminate\Http\Request;
use App\Models\User;

class ApiController extends Controller
{
    public function getPessoas(Request $request) {
        $pessoas = Pessoa::where('congregacao_id', $request->congregacao_id)->get();

        return $pessoas;
    }

    public function getSetors() {
        $setors = Setor::orderBy("id", "asc")
                    ->get();
    }

    public function getCongregacoes($setor_id) {
        $congregacoes = Congregacao::select('id', 'nome')
            ->where('setor_id', '=', $setor_id)
            ->get();
        return $congregacoes;
    }
}
