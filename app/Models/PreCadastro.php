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

    public function getFormattedPhoneNumber()
    {
        $tel = preg_replace('/\D/', '', $this->telefone);

        if (strlen($tel) === 11) {
            return sprintf(
                '(%s) %s-%s',
                substr($tel, 0, 2),
                substr($tel, 2, 5),
                substr($tel, 7, 4)
            );
        }

        return $this->telefone;
    }
}
