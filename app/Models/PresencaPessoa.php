<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PresencaPessoa extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'presenca_pessoas';

    public function pessoa() {
        return $this->belongsTo(Pessoa::class, 'pessoa_id');
    }
    public function funcao() {
        return $this->belongsTo(Funcao::class, 'funcao_id');
    }


}
