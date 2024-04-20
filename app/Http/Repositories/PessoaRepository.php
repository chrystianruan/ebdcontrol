<?php

namespace App\Http\Repositories;

use App\Http\Enums\FuncaoEnum;
use App\Http\Enums\StatusEnum;
use App\Models\Pessoa;
use App\Models\PessoaSala;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class PessoaRepository
{

    public function findByInteresseAndCongregacaoAndSalaCount($salaId = null) : ?Collection {
        $professores = $this->findByFuncaoUnique(FuncaoEnum::PROFESSOR->value);
        $pessoas = Pessoa::join('pessoa_salas', 'pessoas.id', '=', 'pessoa_salas.pessoa_id')
            ->where('interesse', '<>', 2)
            ->where('pessoa_salas.funcao_id', FuncaoEnum::ALUNO->value)
            ->whereNotIn('pessoas.id', $professores->map(function ($professor) { return $professor->pessoa_id; }))
            ->where('congregacao_id', '=', auth()->user()->congregacao_id);

        if ($salaId !== null) {
            $pessoas = $pessoas->where('pessoa_salas.sala_id', $salaId);
        }

        return $pessoas->get();
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

    public function findByFuncaoIdCount(int $funcaoId, int $salaId = null) : ?array{
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
		            p.congregacao_id = $userLogado->congregacao_id";

        if ($salaId != null) {
            $query .= " and ps.sala_id = $salaId";
        }

        $query .= " group by pessoa_id) c";

        return DB::select($query);
    }
    public function getAniversariantesMes(int $salaId = null) : ?Collection {
        $pessoas = PessoaSala::join('pessoas', 'pessoas.id', '=', 'pessoa_salas.pessoa_id')
            ->where('pessoas.congregacao_id', auth()->user()->congregacao_id)
            ->whereMonth('data_nasc', '=',  date('n'));

        if ($salaId != null) {
            $pessoas = $pessoas->where('pessoa_salas.sala_id', $salaId);
        }

        return $pessoas->groupBy('pessoa_salas.pessoa_id')->get();
    }

    public function getInativos(int $salaId = null) : ?Collection {
        $pessoas = PessoaSala::join('pessoas', 'pessoas.id', '=', 'pessoa_salas.pessoa_id')
            ->where('pessoas.congregacao_id', '=', auth()->user()->congregacao_id)
            ->where('pessoas.situacao', '=', StatusEnum::INATIVO->value);

        if ($salaId != null) {
            $pessoas = $pessoas->where('pessoa_salas.sala_id', $salaId);
        }

        return $pessoas->groupBy('pessoa_salas.pessoa_id')->get();
    }

    public function findBySalaIdAndSituacao(int $salaId, int $situacao = 1) : ?Collection {
        return PessoaSala::join('pessoas', 'pessoas.id', '=', 'pessoa_salas.pessoa_id')
                    ->where('pessoa_salas.sala_id', $salaId)
                    ->where('pessoas.situacao', $situacao)
                    ->groupBy('pessoa_salas.pessoa_id')
                    ->get();
    }
}
