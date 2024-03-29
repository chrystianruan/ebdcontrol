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
use Illuminate\Support\Facades\DB;

class PessoaController extends Controller
{
    private $linkCadastroGeral;
    private $pessoaService;
    private $ufs;
    private $publicos;
    private $formations;

    public function __construct(LinkCadastroGeral $linkCadastroGeral, PessoaService $pessoaService) {
        $this->linkCadastroGeral = $linkCadastroGeral;
        $this->pessoaService = $pessoaService;
        $this->publicos = Publico::all();
        $this->formations = Formation::all();
        $this->ufs = Uf::orderBy("nome")->get();
    }
    public function liberarLinkCadastroGeral(Request $req) {
        return $this->pessoaService->liberarLinkGeral(intval($req->congregacao));
    }

    public function desabilitarLinkCadastroGeral() {

    }
    public function indexCadastroClasse() {
        $title = "Cadastro Admin";
        $check = request('scales');
        $route = "cadastro.pessoa.classe";

        $congregacao = Congregacao::select('*', DB::raw('congregacaos.id as congregacao_id'),DB::raw('setors.id as setor_id'), DB::raw('congregacaos.nome as congregacao_nome'), DB::raw('setors.nome as setor_nome'))
            ->join('setors', 'setors.id', '=', 'congregacaos.setor_id')
            ->findOrFail(auth()->user()->congregacao_id);

        return view('/classe/cadastro-pessoa', ['ufs' => $this->ufs, 'publicos' => $this->publicos,
            'formations' => $this->formations, 'check' => $check, 'congregacao' => $congregacao, 'title' => $title,
            'route' => $route]);
    }

    public function indexCadastroAdmin() {
        $classes = Sala::where('id', '>', 2)
            ->where('congregacao_id', '=', auth()->user()->congregacao_id)
            ->orderBy('nome')->get();

        $title = "Cadastro Admin";
        $check = request('scales');
        $route = "cadastro.pessoa.admin";

        $congregacao = Congregacao::select('*', DB::raw('congregacaos.id as congregacao_id'),DB::raw('setors.id as setor_id'), DB::raw('congregacaos.nome as congregacao_nome'), DB::raw('setors.nome as setor_nome'))
            ->join('setors', 'setors.id', '=', 'congregacaos.setor_id')
            ->findOrFail(auth()->user()->congregacao_id);

        return view('/admin/cadastro/pessoa', ['classes' => $classes, 'ufs' => $this->ufs, 'publicos' => $this->publicos,
            'formations' => $this->formations, 'check' => $check, 'congregacao' => $congregacao, 'title' => $title,
            'route' => $route]);
    }

    public function indexCadastroGeral($congregacaoId) {
        if (!$this->linkCadastroGeral->getLinkActive(base64_decode($congregacaoId))) {
            return abort(404);
        }
        $classes = Sala::where('id', '>', 2)
            ->where('congregacao_id', '=', base64_decode($congregacaoId))
            ->orderBy('nome')->get();
        $title = "Cadastro Geral";
        $route = "cadastro.pessoa.geral";
        $check = request('scales');

        $congregacao = Congregacao::select('*', DB::raw('congregacaos.id as congregacao_id'),DB::raw('setors.id as setor_id'), DB::raw('congregacaos.nome as congregacao_nome'), DB::raw('setors.nome as setor_nome'))
            ->join('setors', 'setors.id', '=', 'congregacaos.setor_id')
            ->findOrFail(base64_decode($congregacaoId));

        return view('/cadastro', ['classes' => $classes, 'ufs' => $this->ufs, 'publicos' => $this->publicos,
            'formations' => $this->formations, 'check' => $check, 'congregacao' => $congregacao, 'title' => $title,
            'route' => $route]);
    }

    public function storeFromAdmin(Request $request) {
        return $this->pessoaService->store($request, intval($request->classe), intval($request->congregacao));
    }
    public function storeFromGeral(Request $request) {
        return $this->pessoaService->store($request, intval($request->classe), intval($request->congregacao));
    }
    public function storeFromClasse(Request $request) {
        return $this->pessoaService->store($request, intval($request->classe), intval($request->congregacao));
    }


}
