<?php

namespace App\Http\Services;

class ChamadaService
{
    public function classesNotSendChamada(object $salas, object $chamadas) : array {
        $classes = [];

        foreach($salas as $sala) {
            foreach($chamadas as $chamada) {
                if ($sala->id != $chamada->id_sala) {
                    $classes[] = ''.$sala->nome;
                }
            }
        }

        return $classes;
    }

    public function convertToInt(object $request) : array {
        $values = [
            "presentes" => intval($request->presentes),
            "visitantes" => intval($request->visitantes),
            "assistenciaTotal" => intval($request->presentes+$request->visitantes),
            "revistas" => intval($request->revistas),
            "biblias" => intval($request->biblias)
        ];

        return $values;
    }

    public function validateRequest(array $data, int $matriculados) : string {
        $presentes = $data['presentes'];
        $visitantes = $data['visitantes'];
        $assistenciaTotal = $presentes+$visitantes;
        $biblias = $data['biblias'];
        $revistas = $data['revistas'];
         if (gettype($presentes) != "integer" || $presentes < 0 || $presentes > $matriculados) {
            return "Número de presentes inválido";
         }
         if (gettype($visitantes) != "integer" || $visitantes < 0) {
             return "Número de visitantes inválido";
         }
         if (gettype($biblias) != "integer" || $biblias < 0 || $biblias > $assistenciaTotal) {
             return "Número de Bíblias inválido";
         }
        if (gettype($revistas) != "integer" || $revistas < 0 || $revistas > $assistenciaTotal) {
            return "Número de revistas inválido";
         }
        return 0;
    }


 }
