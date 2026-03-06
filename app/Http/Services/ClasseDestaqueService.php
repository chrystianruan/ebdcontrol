<?php

namespace App\Http\Services;

use App\Models\Chamada;
use App\Models\Sala;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ClasseDestaqueService
{
    /**
     * Calcula os destaques individuais e o destaque geral a partir de uma coleção de chamadas de um dia.
     *
     * @param Collection $chamadas — chamadas individuais (por classe) de um mesmo dia
     * @return array{
     *     maior_presenca: array{sala: string, valor: float}|null,
     *     maior_visitantes: array{sala: string, valor: int}|null,
     *     maior_biblias: array{sala: string, valor: float}|null,
     *     maior_revistas: array{sala: string, valor: float}|null,
     *     destaque_geral: array{sala: string, pontos: int}|null
     * }
     */
    public function calcularDestaques(Collection $chamadas): array
    {
        $resultado = [
            'maior_presenca'   => null,
            'maior_visitantes' => null,
            'maior_biblias'    => null,
            'maior_revistas'   => null,
            'destaque_geral'   => null,
        ];

        if ($chamadas->isEmpty()) {
            return $resultado;
        }

        $resultado['maior_presenca']   = $this->calcularMaiorPresenca($chamadas);
        $resultado['maior_visitantes'] = $this->calcularMaiorVisitantes($chamadas);
        $resultado['maior_biblias']    = $this->calcularMaiorBiblias($chamadas);
        $resultado['maior_revistas']   = $this->calcularMaiorRevistas($chamadas);
        $resultado['destaque_geral']   = $this->calcularDestaqueGeral($resultado, $chamadas);

        return $resultado;
    }

    /**
     * Calcula os piores índices (classes com pior desempenho) nas 4 categorias.
     */
    public function calcularPioresIndices(Collection $chamadas): array
    {
        $resultado = [
            'pior_presenca'   => null,
            'pior_visitantes' => null,
            'pior_biblias'    => null,
            'pior_revistas'   => null,
        ];

        if ($chamadas->isEmpty()) {
            return $resultado;
        }

        $resultado['pior_presenca']   = $this->calcularPiorPresenca($chamadas);
        $resultado['pior_visitantes'] = $this->calcularPiorVisitantes($chamadas);
        $resultado['pior_biblias']    = $this->calcularPiorBiblias($chamadas);
        $resultado['pior_revistas']   = $this->calcularPiorRevistas($chamadas);

        return $resultado;
    }

    /**
     * Calcula o comparativo entre o relatório atual e o anterior.
     *
     * @param string $currentDate — data do relatório atual (Y-m-d)
     * @param int $congregacaoId
     * @return array{
     *     tem_anterior: bool,
     *     data_anterior: string|null,
     *     comparativo: array|null
     * }
     */
    public function calcularComparativo(string $currentDate, int $congregacaoId): array
    {
        $relatorioAnterior = Chamada::selectRaw('sum(matriculados) as matriculados, sum(presentes) as presentes, sum(visitantes) as visitantes, sum(biblias) as biblias, sum(revistas) as revistas, created_at')
            ->where('congregacao_id', $congregacaoId)
            ->whereDate('created_at', '<', $currentDate)
            ->groupBy(DB::raw('CAST(created_at AS DATE)'))
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$relatorioAnterior) {
            return [
                'tem_anterior'  => false,
                'data_anterior' => null,
                'comparativo'   => null,
            ];
        }

        $relatorioAtual = Chamada::selectRaw('sum(matriculados) as matriculados, sum(presentes) as presentes, sum(visitantes) as visitantes, sum(biblias) as biblias, sum(revistas) as revistas, created_at')
            ->where('congregacao_id', $congregacaoId)
            ->whereDate('created_at', $currentDate)
            ->groupBy(DB::raw('CAST(created_at AS DATE)'))
            ->first();

        if (!$relatorioAtual) {
            return [
                'tem_anterior'  => false,
                'data_anterior' => null,
                'comparativo'   => null,
            ];
        }

        $comparativo = $this->gerarComparativo($relatorioAtual, $relatorioAnterior);

        return [
            'tem_anterior'  => true,
            'data_anterior' => date('d/m/Y', strtotime($relatorioAnterior->created_at)),
            'comparativo'   => $comparativo,
        ];
    }

    /**
     * Gera o array de comparativo entre relatório atual e anterior.
     */
    private function gerarComparativo($atual, $anterior): array
    {
        $assistAtual    = $atual->presentes + $atual->visitantes;
        $assistAnterior = $anterior->presentes + $anterior->visitantes;

        $percPresAtual    = $atual->matriculados > 0 ? round(100 * $atual->presentes / $atual->matriculados, 1) : 0;
        $percPresAnterior = $anterior->matriculados > 0 ? round(100 * $anterior->presentes / $anterior->matriculados, 1) : 0;

        $percBibliasAtual    = $assistAtual > 0 ? round(100 * $atual->biblias / $assistAtual, 1) : 0;
        $percBibliasAnterior = $assistAnterior > 0 ? round(100 * $anterior->biblias / $assistAnterior, 1) : 0;

        $percRevistasAtual    = $assistAtual > 0 ? round(100 * $atual->revistas / $assistAtual, 1) : 0;
        $percRevistasAnterior = $assistAnterior > 0 ? round(100 * $anterior->revistas / $assistAnterior, 1) : 0;

        return [
            'matriculados' => $this->calcVariacao($atual->matriculados, $anterior->matriculados),
            'presentes'    => $this->calcVariacao($atual->presentes, $anterior->presentes),
            'visitantes'   => $this->calcVariacao($atual->visitantes, $anterior->visitantes),
            'assist_total' => $this->calcVariacao($assistAtual, $assistAnterior),
            'biblias'      => $this->calcVariacao($atual->biblias, $anterior->biblias),
            'revistas'     => $this->calcVariacao($atual->revistas, $anterior->revistas),
            'perc_presenca'  => ['atual' => $percPresAtual, 'anterior' => $percPresAnterior, 'variacao' => round($percPresAtual - $percPresAnterior, 1)],
            'perc_biblias'   => ['atual' => $percBibliasAtual, 'anterior' => $percBibliasAnterior, 'variacao' => round($percBibliasAtual - $percBibliasAnterior, 1)],
            'perc_revistas'  => ['atual' => $percRevistasAtual, 'anterior' => $percRevistasAnterior, 'variacao' => round($percRevistasAtual - $percRevistasAnterior, 1)],
        ];
    }

    /**
     * Calcula a variação entre valor atual e anterior.
     */
    private function calcVariacao($atual, $anterior): array
    {
        $diferenca  = $atual - $anterior;
        $percentual = $anterior > 0 ? round(($diferenca / $anterior) * 100, 1) : ($atual > 0 ? 100.0 : 0.0);

        return [
            'atual'      => (int) $atual,
            'anterior'   => (int) $anterior,
            'diferenca'  => (int) $diferenca,
            'percentual' => $percentual,
        ];
    }

    /**
     * Retorna dados completos para o modal de visualizar relatório.
     */
    public function getDadosModalRelatorio(string $date, int $congregacaoId): array
    {
        $chamadas = Chamada::with('sala')
            ->where('congregacao_id', $congregacaoId)
            ->whereDate('created_at', $date)
            ->get();

        $relatorio = Chamada::selectRaw('sum(matriculados) as matriculados, sum(presentes) as presentes, sum(visitantes) as visitantes, sum(biblias) as biblias, sum(revistas) as revistas, created_at')
            ->where('congregacao_id', $congregacaoId)
            ->whereDate('created_at', $date)
            ->groupBy(DB::raw('CAST(created_at AS DATE)'))
            ->first();

        if (!$relatorio || $chamadas->isEmpty()) {
            return ['vazio' => true];
        }

        $destaques   = $this->calcularDestaques($chamadas);
        $piores      = $this->calcularPioresIndices($chamadas);
        $comparativo = $this->calcularComparativo($date, $congregacaoId);

        // Dados individuais por classe
        $classesDados = [];
        foreach ($chamadas as $c) {
            $assistTotal = $c->presentes + $c->visitantes;
            $classesDados[] = [
                'sala'         => $this->getNomeSala($c),
                'matriculados' => (int) $c->matriculados,
                'presentes'    => (int) $c->presentes,
                'visitantes'   => (int) $c->visitantes,
                'assist_total' => $assistTotal,
                'biblias'      => (int) $c->biblias,
                'revistas'     => (int) $c->revistas,
                'perc_presenca' => $c->matriculados > 0 ? round(100 * $c->presentes / $c->matriculados, 1) : 0,
                'perc_biblias'  => $assistTotal > 0 ? round(100 * $c->biblias / $assistTotal, 1) : 0,
                'perc_revistas' => $assistTotal > 0 ? round(100 * $c->revistas / $assistTotal, 1) : 0,
            ];
        }

        $assistTotal = $relatorio->presentes + $relatorio->visitantes;

        // Identificar classes que não enviaram chamada
        $todasSalas = Sala::select('id', 'nome')
            ->where('id', '>', 2)
            ->where('congregacao_id', $congregacaoId)
            ->get();
        $idsComChamada = $chamadas->pluck('id_sala')->toArray();
        $classesFaltantes = [];
        foreach ($todasSalas as $sala) {
            if (!in_array($sala->id, $idsComChamada)) {
                $classesFaltantes[] = ['nome' => $sala->nome];
            }
        }

        return [
            'vazio'       => false,
            'data'        => date('d/m/Y', strtotime($relatorio->created_at)),
            'resumo'      => [
                'matriculados' => (int) $relatorio->matriculados,
                'presentes'    => (int) $relatorio->presentes,
                'visitantes'   => (int) $relatorio->visitantes,
                'assist_total' => $assistTotal,
                'biblias'      => (int) $relatorio->biblias,
                'revistas'     => (int) $relatorio->revistas,
                'perc_presenca' => $relatorio->matriculados > 0 ? round(100 * $relatorio->presentes / $relatorio->matriculados, 1) : 0,
                'perc_biblias'  => $assistTotal > 0 ? round(100 * $relatorio->biblias / $assistTotal, 1) : 0,
                'perc_revistas' => $assistTotal > 0 ? round(100 * $relatorio->revistas / $assistTotal, 1) : 0,
            ],
            'classes'         => $classesDados,
            'classesFaltantes' => $classesFaltantes,
            'destaques'   => $destaques,
            'piores'      => $piores,
            'comparativo' => $comparativo,
        ];
    }

    /**
     * Classe com maior percentual de presentes (presentes / matriculados * 100).
     */
    private function calcularMaiorPresenca(Collection $chamadas): ?array
    {
        $melhor = null;
        $melhorValor = -1;

        foreach ($chamadas as $chamada) {
            if ($chamada->matriculados <= 0) {
                continue;
            }

            $percentual = round(($chamada->presentes / $chamada->matriculados) * 100, 1);

            if ($percentual > $melhorValor) {
                $melhorValor = $percentual;
                $melhor = [
                    'sala'  => $this->getNomeSala($chamada),
                    'valor' => $percentual,
                ];
            }
        }

        return $melhor;
    }

    /**
     * Classe com maior número de visitantes.
     */
    private function calcularMaiorVisitantes(Collection $chamadas): ?array
    {
        $melhor = null;
        $melhorValor = -1;

        foreach ($chamadas as $chamada) {
            if ($chamada->visitantes > $melhorValor) {
                $melhorValor = $chamada->visitantes;
                $melhor = [
                    'sala'  => $this->getNomeSala($chamada),
                    'valor' => (int) $chamada->visitantes,
                ];
            }
        }

        return $melhor;
    }

    /**
     * Classe com maior percentual de bíblias (biblias / assist_total * 100).
     */
    private function calcularMaiorBiblias(Collection $chamadas): ?array
    {
        $melhor = null;
        $melhorValor = -1;

        foreach ($chamadas as $chamada) {
            $assistTotal = $chamada->presentes + $chamada->visitantes;
            if ($assistTotal <= 0) {
                continue;
            }

            $percentual = round(($chamada->biblias / $assistTotal) * 100, 1);

            if ($percentual > $melhorValor) {
                $melhorValor = $percentual;
                $melhor = [
                    'sala'  => $this->getNomeSala($chamada),
                    'valor' => $percentual,
                ];
            }
        }

        return $melhor;
    }

    /**
     * Classe com maior percentual de revistas (revistas / assist_total * 100).
     */
    private function calcularMaiorRevistas(Collection $chamadas): ?array
    {
        $melhor = null;
        $melhorValor = -1;

        foreach ($chamadas as $chamada) {
            $assistTotal = $chamada->presentes + $chamada->visitantes;
            if ($assistTotal <= 0) {
                continue;
            }

            $percentual = round(($chamada->revistas / $assistTotal) * 100, 1);

            if ($percentual > $melhorValor) {
                $melhorValor = $percentual;
                $melhor = [
                    'sala'  => $this->getNomeSala($chamada),
                    'valor' => $percentual,
                ];
            }
        }

        return $melhor;
    }

    /**
     * Classe destaque geral: a que mais apareceu como destaque nos 4 critérios.
     * Em caso de empate, o desempate é feito pela maior quantidade de presentes.
     */
    private function calcularDestaqueGeral(array $destaques, Collection $chamadas): ?array
    {
        $pontuacao = [];

        $categorias = ['maior_presenca', 'maior_visitantes', 'maior_biblias', 'maior_revistas'];

        foreach ($categorias as $categoria) {
            if (!empty($destaques[$categoria]['sala'])) {
                $sala = $destaques[$categoria]['sala'];
                if (!isset($pontuacao[$sala])) {
                    $pontuacao[$sala] = 0;
                }
                $pontuacao[$sala]++;
            }
        }

        if (empty($pontuacao)) {
            return null;
        }

        // Montar mapa de presenças por nome de sala para desempate
        $presencasPorSala = [];
        foreach ($chamadas as $chamada) {
            $nomeSala = $this->getNomeSala($chamada);
            if (!isset($presencasPorSala[$nomeSala])) {
                $presencasPorSala[$nomeSala] = 0;
            }
            $presencasPorSala[$nomeSala] += (int) $chamada->presentes;
        }

        // Ordenar: primeiro por pontuação (desc), depois por presenças (desc) como desempate
        uksort($pontuacao, function ($a, $b) use ($pontuacao, $presencasPorSala) {
            if ($pontuacao[$a] !== $pontuacao[$b]) {
                return $pontuacao[$b] - $pontuacao[$a];
            }
            $presA = $presencasPorSala[$a] ?? 0;
            $presB = $presencasPorSala[$b] ?? 0;
            return $presB - $presA;
        });

        $salaCampeao = array_key_first($pontuacao);

        return [
            'sala'   => $salaCampeao,
            'pontos' => $pontuacao[$salaCampeao],
        ];
    }

    /**
     * Classe com menor percentual de presentes (presentes / matriculados * 100).
     */
    private function calcularPiorPresenca(Collection $chamadas): ?array
    {
        $pior = null;
        $piorValor = PHP_FLOAT_MAX;

        foreach ($chamadas as $chamada) {
            if ($chamada->matriculados <= 0) {
                continue;
            }
            $percentual = round(($chamada->presentes / $chamada->matriculados) * 100, 1);
            if ($percentual < $piorValor) {
                $piorValor = $percentual;
                $pior = [
                    'sala'  => $this->getNomeSala($chamada),
                    'valor' => $percentual,
                ];
            }
        }

        return $pior;
    }

    /**
     * Classe com menor número de visitantes.
     */
    private function calcularPiorVisitantes(Collection $chamadas): ?array
    {
        $pior = null;
        $piorValor = PHP_INT_MAX;

        foreach ($chamadas as $chamada) {
            if ($chamada->visitantes < $piorValor) {
                $piorValor = $chamada->visitantes;
                $pior = [
                    'sala'  => $this->getNomeSala($chamada),
                    'valor' => (int) $chamada->visitantes,
                ];
            }
        }

        return $pior;
    }

    /**
     * Classe com menor percentual de bíblias (biblias / assist_total * 100).
     */
    private function calcularPiorBiblias(Collection $chamadas): ?array
    {
        $pior = null;
        $piorValor = PHP_FLOAT_MAX;

        foreach ($chamadas as $chamada) {
            $assistTotal = $chamada->presentes + $chamada->visitantes;
            if ($assistTotal <= 0) {
                continue;
            }
            $percentual = round(($chamada->biblias / $assistTotal) * 100, 1);
            if ($percentual < $piorValor) {
                $piorValor = $percentual;
                $pior = [
                    'sala'  => $this->getNomeSala($chamada),
                    'valor' => $percentual,
                ];
            }
        }

        return $pior;
    }

    /**
     * Classe com menor percentual de revistas (revistas / assist_total * 100).
     */
    private function calcularPiorRevistas(Collection $chamadas): ?array
    {
        $pior = null;
        $piorValor = PHP_FLOAT_MAX;

        foreach ($chamadas as $chamada) {
            $assistTotal = $chamada->presentes + $chamada->visitantes;
            if ($assistTotal <= 0) {
                continue;
            }
            $percentual = round(($chamada->revistas / $assistTotal) * 100, 1);
            if ($percentual < $piorValor) {
                $piorValor = $percentual;
                $pior = [
                    'sala'  => $this->getNomeSala($chamada),
                    'valor' => $percentual,
                ];
            }
        }

        return $pior;
    }

    /**
     * Obtém o nome da sala de uma chamada (via relationship ou atributo direto).
     */
    private function getNomeSala($chamada): string
    {
        if (isset($chamada->sala) && $chamada->sala) {
            return $chamada->sala->nome;
        }

        if (isset($chamada->nome)) {
            return $chamada->nome;
        }

        return 'Classe desconhecida';
    }
}

