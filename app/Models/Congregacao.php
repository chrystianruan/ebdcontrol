<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Congregacao extends Model
{
    use HasFactory;

    public function salas() : HasMany
    {
        return $this->hasMany(Sala::class);
    }
    public function linkCadastroGeral() : HasOne {
        return $this->hasOne(LinkCadastroGeral::class);
    }
}
