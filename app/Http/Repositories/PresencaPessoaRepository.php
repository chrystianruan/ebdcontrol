<?php

namespace App\Http\Repositories;

use App\Http\DTOs\PresencaPessoaDTO;
use App\Models\PresencaPessoa;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class PresencaPessoaRepository
{
    public function findByPessoaIdAndToday(int $pessoaId) :  ?PresencaPessoa {
        return PresencaPessoa::where('pessoa_id', $pessoaId)
            ->whereDate('created_at', date('Y-m-d'))
            ->first();
    }

    public function findByPessoaId(int $pessoaId) : ?Collection {
        return PresencaPessoa::where('pessoa_id', $pessoaId)
            ->get();
    }

    public function findByMonthAndYearAndSalaId(string $dataInicio, string $dataFim, int $salaId, array $orderBy) : ?Collection {
         $presencaPessoa = PresencaPessoa::select('pessoas.nome as pessoa_nome', 'funcaos.nome as funcao_nome', DB::raw('sum(presente) as presencas'), 'pessoas.data_nasc as data_nascimento')
             ->join('pessoas', 'pessoas.id', '=', 'presenca_pessoas.pessoa_id')
             ->join('funcaos', 'funcaos.id', '=', 'presenca_pessoas.funcao_id');

         if ($dataInicio && $dataFim) {
             $presencaPessoa = $presencaPessoa->whereBetween('presenca_pessoas.created_at',  [$dataInicio, $dataFim]);
         }
         if ($salaId) {
             $presencaPessoa = $presencaPessoa->where('sala_id', $salaId);
         }

         $presencaPessoa = $presencaPessoa->orderBy($orderBy['column'], $orderBy['type'])
             ->groupBy('presenca_pessoas.pessoa_id')
             ->get();

         return $presencaPessoa;

    }

    public function findByDateAndSala(string $date, int $salaId) :  ?\Illuminate\Database\Eloquent\Collection {
        return PresencaPessoa::whereDate('presenca_pessoas.created_at', $date)
            ->where('sala_id', $salaId)
            ->join('pessoas', 'pessoas.id', '=', 'presenca_pessoas.pessoa_id')
            ->orderBy('pessoas.nome')
            ->get();
    }


}
