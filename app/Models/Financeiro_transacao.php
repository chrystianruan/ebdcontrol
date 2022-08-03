<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Financeiro_transacao extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $dates = ['data_cad'];
}
