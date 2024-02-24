<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class  LinkCadastroGeral extends Model
{
    protected $table = "link_cadastro_geral";

    public function getLinksActive() {
        return LinkCadastroGeral::where('active')
            ->get();
    }
    public function getLink(int $congregacaoId) {
        return LinkCadastroGeral::where('congregacao_id', $congregacaoId)
            ->first();
    }
    public function getLinkActive(int $congregacaoId) {
        return LinkCadastroGeral::where('congregacao_id', $congregacaoId)
            ->where('active', 1)
            ->first();
    }


}
