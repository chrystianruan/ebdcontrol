<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Relatorio extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts = [
        'salas' => 'array'
    ];

    public function ultimoRegistro() {
        return Relatorio::where('congregacao_id', auth()->user()->congregacao_id)->latest()->first();
    }
}
