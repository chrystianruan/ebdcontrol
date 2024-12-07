<?php

namespace App\Http\Utils;

use App\Http\Repositories\UserRepository;
use App\Http\Repositories\UsuarioExternoRepository;
use App\Models\User;

class GenerateMatricula
{
    private $userRepository;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    public function getMatricula(int $congregacao) : string {
        $year = date('Y');
        $month = date('m');
        if ($congregacao < 10) {
            $congregacao = "0".$congregacao;
        }
        $randomNumber = rand(10000, 99999);

        $matricula = $this->generateStringMatricula($year, $month, $congregacao, $randomNumber);

        if ($this->userRepository->existsByMatricula($matricula)->count() > 0) {
            return $this->getMatricula($congregacao);
        }

        return $matricula;
    }

    public function generateStringMatricula(int $year, int $month, int $congregacao, int $randomNumber) : string {
        $word = $year.$month.$congregacao.$randomNumber;

        return $word;
    }


}
