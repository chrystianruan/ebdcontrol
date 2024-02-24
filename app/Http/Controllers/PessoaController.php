<?php

namespace App\Http\Controllers;

use App\Http\Services\PessoaService;
use App\Models\Congregacao;
use App\Models\Formation;
use App\Models\LinkCadastroGeral;
use App\Models\Publico;
use App\Models\Sala;
use App\Models\Uf;
use Illuminate\Http\Request;
use Termwind\Components\Li;

class PessoaController extends Controller
{
    private $linkCadastroGeral;
    private $pessoaService;

    public function __construct(LinkCadastroGeral $linkCadastroGeral, PessoaService $pessoaService) {
        $this->linkCadastroGeral = $linkCadastroGeral;
        $this->pessoaService = $pessoaService;
    }
    public function liberarLinkCadastroGeral(Request $req) {
        return $this->pessoaService->liberarLinkGeral(intval($req->congregacao));
    }

    public function desabilitarLinkCadastroGeral() {

    }

    public function indexCadastroGeral($congregacaoId) {
        if (!$this->linkCadastroGeral->getLinkActive(base64_decode($congregacaoId))) {
            return abort(404);
        }
        $check = request('scales');
        $classes = Sala::where('id', '>', 2)
            ->where('congregacao_id', '=', base64_decode($congregacaoId))
            ->orderBy('nome')->get();
        $ufs = Uf::orderBy("nome")->get();
        $publicos = Publico::all();
        $formations = Formation::all();

        $congregacao = Congregacao::findOrFail(base64_decode($congregacaoId));

        return view('/cadastro', ['classes' => $classes, 'ufs' => $ufs, 'publicos' => $publicos,
            'formations' => $formations, 'check' => $check, 'congregacao' => $congregacao]);
    }

    public function storeOfAdmin(Request $request) {

    }
    public function storeOfGeral(Request $request) {
        return $this->pessoaService->store($request, intval($request->classe), intval($request->congregacao));
    }
    public function storeOfClasse(Request $request) {

    }

}
