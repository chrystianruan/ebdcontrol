<?php

namespace App\Http\Repositories;

use App\Models\User;

class UserRepository
{
    public function existsByMatricula(string $matricula)
    {
        return User::where('matricula', $matricula)->get();
    }
}
