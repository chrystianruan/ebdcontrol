<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sala extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $guarded = [];

    public function congregacao() : BelongsTo {
        return $this->belongsTo(Congregacao::class);
    }

    public function pessoas() : BelongsToMany {
        return $this->belongsToMany(Pessoa::class, 'pessoas_salas', 'sala_id', 'pessoa_id');
    }

    public function chamadas() : HasMany {
        return $this->hasMany(Chamada::class);
    }
}
