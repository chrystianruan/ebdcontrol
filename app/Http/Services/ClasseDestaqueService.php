<?php

namespace App\Http\Services;

use Illuminate\Database\Eloquent\Collection;

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

