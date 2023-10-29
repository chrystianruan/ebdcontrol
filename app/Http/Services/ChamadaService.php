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
 }
