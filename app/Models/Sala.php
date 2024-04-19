<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Sala extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function pessoas() : BelongsToMany {
        return $this->belongsToMany(Pessoa::class, 'pessoas_salas', 'sala_id', 'pessoa_id');
    }
}
