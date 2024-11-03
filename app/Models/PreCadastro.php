<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PreCadastro extends Model
{
    use HasFactory;

    protected $table = 'pre_cadastro_pessoas';

    public function sala() : BelongsTo {
        return $this->belongsTo(Sala::class, 'classe', 'id');
    }
}
