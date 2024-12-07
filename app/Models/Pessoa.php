<?php

namespace App\Models;

use App\Http\Repositories\PresencaPessoaRepository;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pessoa extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $guarded = [];
    protected $casts = [
        'id_sala' => 'array'
    ];

    protected $dates = ['data_nasc'];

    public function salas() : BelongsToMany {
        return $this->belongsToMany(Sala::class, 'pessoa_salas', 'pessoa_id', 'sala_id');
    }

    public function funcoes() : BelongsToMany {
        return $this->belongsToMany(Funcao::class, 'pessoa_salas', 'pessoa_id', 'funcao_id');
    }

    public function funcao($pessoaId) : ?PessoaSala {
        return PessoaSala::select('funcaos.nome as funcao_nome')
            ->join('funcaos', 'funcao.id', '=', 'pessoa_salas.funcao_id')
            ->where('pessoa_salas.pessoa_id', $pessoaId)
            ->first();
    }

    public function presencas() : HasMany {
        return $this->hasMany(PresencaPessoa::class);
    }

    public function user() : HasOne {
        return $this->hasOne(User::class);
    }

    public function presente() : bool {
        $presencaPessoaRepository = new PresencaPessoaRepository();
        if ($presencaPessoaRepository->findByPessoaIdAndToday($this->id)) {
            if ($presencaPessoaRepository->findByPessoaIdAndToday($this->id)->presente) {
                return true;
            }
        }
        return false;
    }

}
