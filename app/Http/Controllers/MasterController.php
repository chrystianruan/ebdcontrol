<?php

namespace App\Http\Controllers;

use App\Http\Repositories\ChamadaDiaCongregacaoRepository;
use App\Http\Utils\PermissaoEnum;
use App\Models\ChamadaDiaCongregacao;
use App\Models\Congregacao;
use App\Models\LinkCadastroGeral;
use App\Models\Permissao;
use App\Models\Pessoa;
use http\Env\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Sala;
use Carbon\Carbon;
use DB;
use Illuminate\View\View;
use phpDocumentor\Reflection\DocBlock\Tags\Author;

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

    public function forceResetPassword($userId) : JsonResponse {
        $user = User::findOrFail($userId);
        if($user->id !== 1 || auth()->user()->congregacao_id !== $user->congregacao_id) {
            $password = bin2hex(random_bytes(3));
            $user->password = bcrypt($password);
            $user->password_temp = $password;
            $user->reset_password = true;
            $user->save();
            return response()->json([
                'response' => 'Senha de usuário resetada com sucesso'
            ], 201);
        } else {
            return response()->json([
                'response' => 'Não é possível resetar a senha do usuário administrador'
            ], 403);
        }
    }

    public function indexUsuarioMaster() {

        $dataAtual = date('d/m/Y');
        $niveisRestricted = Sala::where('id', '=', 1)
            ->orWhere('id', '=', 2);
        $niveis = Sala::where('congregacao_id', '=', auth()->user()->congregacao_id)
            ->orderBy('nome')
            ->union($niveisRestricted)
            ->get();
        return view('/master/cadastro/usuario', ['niveis' => $niveis, 'dataAtual' => $dataAtual]);
    }

    public function searchUserMaster(Request $request) {
        $nome = request('nome');
        $status = request('status') != null ? (int) request('status') == 0 ? "Ativo" : "Inativo" : null;
        $permission = request('permission') ? Permissao::find(request('permission'))->name : null;
        $sala = request('sala') ? Sala::find(request('sala'))->nome : null;
        $permissoes = Permissao::where('id', '>', 1)
            ->get();

        $users = User::where('permissao_id', '<>', PermissaoEnum::SUPERMASTER)
            ->where('users.congregacao_id', '=', auth()->user()->congregacao_id)
            ->leftJoin('pessoas', 'pessoas.id', '=', 'users.pessoa_id');

        if($request->nome) {
            $users = $users->where([['pessoas.nome', 'like', '%'.$request -> nome.'%']]);
        }
        if($request->permission) {
            $users = $users->where('permissao_id', '=', $request->permission);
        }
        if ($request->status) {
            $users = $users->where('status', '=', (bool) $request->status);
        }
        if ($request->sala) {
            $users = $users->where('sala_id', '=', $request->sala);
        }

        $users = $users->orderBy('pessoas.nome')
            ->get();

        return view('/master/filtro/usuario', ['users' => $users, 'nome' => $nome,
            'status' => $status, 'permissoes' => $permissoes, 'permission' => $permission, 'sala' => $sala]);

    }

    public function editUserMaster($id) {
        $user = User::findOrFail($id);
        if($user->id !== 1) {
            $niveis = Permissao::where('id', '>', 1)
                ->get();
            return view('/master/edit/usuario', ['user' => $user, 'niveis' => $niveis]);
        } else {
            return redirect()->back();
        }
    }

    public function updateUserMaster(Request $request) {
        $lastNivel = Permissao::orderBy('id', 'desc')
            ->first();
        $this->validate($request, [
            'id_nivel' => ['required', 'integer', 'min:2', 'max:'.$lastNivel -> id],
            'status' => ['required', 'integer', 'min:0', 'max: 1']
        ], [
            'id_nivel.required' =>  'Nível é obrigatório.',
            'id_nivel.integer' =>  'Esse nível não pode ser cadastrado.',
            'id_nivel.min' =>  'Esse nível não pode ser cadastrado.',
            'id_nivel.max' =>  'Esse nível não pode ser cadastrado.',
            'status.required' =>  'Status é obrigatório.',
            'status.integer' =>  'Esse Status não pode ser cadastrado.',
            'status.min' =>  'Esse Status não pode ser cadastrado.',
            'status.max' =>  'Esse Status não pode ser cadastrado.',

        ]);
        $user = User::findOrFail($request->id);
        if($user->id !== 1) {
            User::findOrFail($request->id)->update([
                'permissao_id' => $request->id_nivel,
                'status' => $request->status,
                'sala_id' => $request->sala
            ]);
            return redirect('/master/filtro/usuario')->with('msg', 'Usuário atualizado com sucesso.');
        } else {
            return redirect()->back();
        }


    }


}
