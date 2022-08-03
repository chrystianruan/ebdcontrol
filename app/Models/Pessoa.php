<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pessoa extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts = [
        'id_sala' => 'array'
    ];

    protected $dates = ['data_nasc'];
}
