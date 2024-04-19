<?php

namespace App\Http\Repositories;

use App\Http\Enums\FuncaoEnum;
use App\Models\Pessoa;
use App\Models\PessoaSala;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class PessoaRepository
{
    //[collect(array_column($professores, 'pessoa_id'))->map(function ($val) {return (int) $val;})])
    public function findByInteresseAndCongregacaoCount() : ?int {
        $professores = $this->findByFuncaoUnique(FuncaoEnum::PROFESSOR->value);
        return Pessoa::join('pessoa_salas', 'pessoas.id', '=', 'pessoa_salas.pessoa_id')
            ->where('interesse', '<>', 2)
            ->where('pessoa_salas.funcao_id', FuncaoEnum::ALUNO->value)
            ->whereNotIn('pessoas.id', $professores->map(function ($professor) { return $professor->pessoa_id; }))
            ->where('congregacao_id', '=', auth()->user()->congregacao_id)
            ->count();
    }

    public function findByFuncaoUnique(int $funcaoId)  {
        return PessoaSala::join('pessoas', 'pessoas.id', '=', 'pessoa_salas.pessoa_id')
            ->where('funcao_id', $funcaoId)
            ->where('congregacao_id', auth()->user()->congregacao_id)
            ->groupBy('pessoa_id')
            ->get('pessoa_id');
    }

    public function getSalasOfPessoa(int $pessoaId) : ?Collection {
        return PessoaSala::select('pessoa_salas.id', DB::raw('salas.id as sala_id'), DB::raw('funcaos.id as funcao_id'))
            ->where('pessoa_id', $pessoaId)
            ->join('salas', 'salas.id', '=', 'pessoa_salas.sala_id')
            ->join('funcaos', 'funcaos.id', '=', 'pessoa_salas.funcao_id')
            ->get();
    }

    public function findByFuncaoIdCount($funcaoId) : ?array{
        $userLogado = auth()->user();
        $query =
            "select
                count(c.id) as quantidade_pessoas, c.funcao_nome as funcao_nome
             from
                (select
                    ps.id, f.nome as funcao_nome
                 from
                     pessoa_salas ps
                 inner join
                         funcaos f on f.id = ps.funcao_id
                 inner join
                         pessoas p on p.id = ps.pessoa_id
                 where
                     ps.funcao_id = $funcaoId
                 and
		            p.congregacao_id = $userLogado->congregacao_id
                 group by
                     pessoa_id) c";

        return DB::select($query);
    }
}
