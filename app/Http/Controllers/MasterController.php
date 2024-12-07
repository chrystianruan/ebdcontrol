<?php

namespace App\Http\Controllers;

use App\Http\Repositories\ChamadaDiaCongregacaoRepository;
use App\Models\ChamadaDiaCongregacao;
use App\Models\Congregacao;
use App\Models\LinkCadastroGeral;
use App\Models\Pessoa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Sala;
use Carbon\Carbon;
use DB;
use Illuminate\View\View;

class MasterController extends Controller
{

    private $linkCadastroGeral;
    private $chamadaDiaCongregacaoRepository;
    public function __construct(LinkCadastroGeral $linkCadastroGeral, ChamadaDiaCongregacaoRepository $chamadaDiaCongregacaoRepository) {
        $this->linkCadastroGeral = $linkCadastroGeral;
        $this->chamadaDiaCongregacaoRepository = $chamadaDiaCongregacaoRepository;
    }
    public function dashboardMaster() {
        $qtdUsersAtivos = User::select(DB::raw('count(users.id) as qtd, permissao_id, salas.nome as niveis'))
        ->leftJoin('salas', 'salas.id', '=', 'users.sala_id')
        ->where('status', false)
        ->where('users.congregacao_id', '=', auth()->user()->congregacao_id)
        ->where('users.id', '>', 1)
        ->groupBy('permissao_id')
        ->get();
        $qtdUsersInativos = User::select(DB::raw('count(users.id) as qtd, permissao_id, salas.nome as niveis'))
        ->leftJoin('salas', 'salas.id', '=', 'users.sala_id')
        ->where('status', true)
        ->where('users.congregacao_id', '=', auth()->user()->congregacao_id)
        ->where('users.id', '>', 1)
        ->groupBy('permissao_id')
        ->get();

        $linkAtivo = $this->linkCadastroGeral->getLinkActive(auth()->user()->congregacao_id);
        $chamadasLiberadasMes = $this->chamadaDiaCongregacaoRepository->findChamadasLiberadasByCongregacaoAndMonth(auth()->user()->congregacao_id, date('n'));

        return view('/master/dashboard', ['qtdUsersAtivos' => $qtdUsersAtivos,
         'qtdUsersInativos' => $qtdUsersInativos, 'linkAtivo' => $linkAtivo,
        'chamadasLiberadasMes' => $chamadasLiberadasMes
        ]);
    }

    public function indexSalaMaster() {
        $dataAtual = date('d/m/Y');
        return view('/master/cadastro/classe', ['dataAtual' => $dataAtual]);
    }

    public function storeSalaMaster(Request $request) {
        $this->validate($request, [
            'nome' => ['required'],
            'tipo' => ['required'],
        ], [
            'nome.required' =>  'Nome é obrigatório.',
            'tipo.required' =>  'Tipo é obrigatório.',
        ]);

        $sala = new Sala;
        $sala -> nome = $request->nome;
        $sala -> tipo = $request->tipo;
        $sala -> congregacao_id = auth()->user()->congregacao_id;
        $sala -> save();
        return redirect('/master/cadastro/classe')->with('msg', 'Sala cadastrada com sucesso');
    }

    public function searchSalaMaster(Request $request) {
        $salap = request('sala');

        if(isset($request->sala)) {
            $salas = Sala::where('id', '=', $request->sala)
            ->where('congregacao_id', '=', auth()->user()->congregacao_id)
            ->get();
        } else {
            $salas = Sala::where('congregacao_id', '=', auth()->user()->congregacao_id)
            ->orderBy('nome')
            ->get();
        }
        $classes = Sala::where('congregacao_id', '=', auth()->user()->congregacao_id)
            ->orderBy("nome")
            ->get();
        return view('/master/filtro/classe',['salas' => $salas, 'salap' => $salap, 'classes' => $classes]);
    }

    public function editSalaMaster($id) {

        $dataAtual = date('Y-m-d');
        $sala = Sala::findOrFail($id);
        if($id == 1 || $id == 2) {
            return redirect('/admin/filtro/sala')->with('msg2', 'Essa sala não pode ser editada ou excluída');
        }

        return view('/master/edit/classe', ['sala' => $sala, 'dataAtual' => $dataAtual]);

    }

    public function updateSalaMaster(Request $request){
        $this->validate($request, [
            'nome' => ['required'],
            'tipo' => ['required'],
        ], [
            'nome.required' =>  'Nome é obrigatório.',
            'tipo.required' =>  'Tipo é obrigatório.',
        ]);
        Sala::findOrFail($request -> id)->update([
            'nome' => $request->nome,
            'tipo' => $request->tipo,
            'congregacao_id' => auth()->user()->congregacao_id
        ]);
        return redirect('/master/filtro/classe')->with('msg', 'Sala foi atualizada com sucesso');
    }

    public function destroySalaMaster($id) {
        Sala::findOrFail($id)->delete();
        return redirect('/master/filtro/classe')->with('msg', 'Sala deletada com sucesso');

    }

    public function indexConfiguracoesPessoas()
    {
        $pessoas = Pessoa::orderBy('nome')
            ->where('congregacao_id', '=', auth()->user()->congregacao_id)
            ->get();
        $salas = Sala::where('id', '>', 2)
            ->where('congregacao_id', '=', auth()->user()->congregacao_id)
            ->orderBy('nome')
            ->get();
        $dataAtual = date('Y-m-d');
        $meses_abv = [1 => 'Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'];
        return view('/master/configuracoes/pessoas', ['pessoas' => $pessoas, 'meses_abv' => $meses_abv, 'salas' => $salas, 'dataAtual' => $dataAtual]);
    }

    public function indexConfiguracoesCongregacao() : View {
        $congregacao = Congregacao::select('setors.nome as setor', 'congregacaos.nome as congregacao', 'congregacaos.id as id', 'congregacaos.latitude', 'congregacaos.longitude')
            ->join('setors', 'setors.id', '=', 'congregacaos.setor_id')
            ->findOrFail(auth()->user()->congregacao_id);
        $matriculados = Pessoa::where('congregacao_id', '=', auth()->user()->congregacao_id)
            ->count();
        $ativos = Pessoa::where('congregacao_id', '=', auth()->user()->congregacao_id)
            ->where('situacao', '=', 1)
            ->count();
        $inativos = Pessoa::where('congregacao_id', '=', auth()->user()->congregacao_id)
            ->where('situacao', '=', 2)
            ->count();
        return view('/master/configuracoes/congregacao', compact(['congregacao', 'matriculados', 'ativos', 'inativos']));
    }

    public function salvarLocalizacao(Request $request) : RedirectResponse
    {
        $congregacao = Congregacao::findOrFail(auth()->user()->congregacao_id);
        $congregacao->latitude = $request->latitude;
        $congregacao->longitude = $request->longitude;
        $congregacao->save();

        return redirect()->back()->with('msg', 'Localização salva com sucesso');
    }



}
