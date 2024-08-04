<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Chamada extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts = [
        'nomes' => 'array',
        'presencas' => 'array'
    ];

    public function sala() : BelongsTo {
        return $this->belongsTo(Sala::class, 'id_sala');
    }
}
