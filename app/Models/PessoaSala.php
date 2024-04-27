<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PessoaSala extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = "pessoa_salas";
}
