<?php

namespace App\Http\Controllers;

use App\Models\Congregacao;
use App\Models\Permissao;
use App\Models\Pessoa;
use App\Models\Sala;
use App\Models\Setor;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SuperMasterController extends Controller
{
    public function index() {
        $setores = Setor::orderBy('id', 'asc')->get();
        return view('super-master.dashboard', compact(['setores']));
    }

    public function userFilters(Request $request) {
        $nome = $request->nome;
        $setor = $request->setor ? Setor::findOrFail((int) $request->setor)->nome : null;
        $congregacao = $request->congregacao ? Congregacao::findOrFail((int) $request->congregacao)->nome : null;
        $permission = $request->permission ? Permissao::findOrFail((int) $request->permission)->name : null;
        $status = $request->status != null ? $request->status == 0 ? "Ativo" : "Inativo" : null;
        $setores = Setor::orderBy("nome")
            ->get();
        $permissoes = Permissao::all();
        $users = User::select('users.id as user_id','pessoas.*', 'users.*', 'congregacaos.nome as nome_congregacao', 'setors.nome as nome_setor')
            ->leftJoin('pessoas' , 'users.pessoa_id', '=', 'pessoas.id')
            ->join("congregacaos", 'congregacaos.id', '=', 'users.congregacao_id')
            ->join("setors", 'setors.id', '=', 'congregacaos.setor_id')
            ->where('users.id', '>', 1);

        if ($request->congregacao) {
            $users = $users->where('users.congregacao_id', '=', $request->congregacao);
        }
        if ($request->setor) {
            $users = $users->where('setors.id', '=', $request->setor);
        }
        if ($request->nome) {
            $users = $users->where("pessoas.nome", "like",'%'.$request->nome.'%');
        }
        if ($request->permission) {
            $users = $users->where("users.permissao_id", '=', $request->permission);
        }
        if ($request->status != null) {
            $users = $users->where("users.status", '=', $request->status);
        }
        $users = $users->orderBy("pessoas.nome")
            ->get();

        return view('super-master.filters.users', compact(['users', 'setores', 'permissoes', 'nome', 'setor', 'permission', 'status', 'congregacao']));

    }

    public function editUserSuperMaster($id) {
        $user = User::select('users.*', 'congregacaos.nome as congregacao_nome', 'setors.nome as setor_nome', 'setors.id as setor_id')
            ->join("congregacaos", 'congregacaos.id', '=', 'users.congregacao_id')
            ->join("setors", 'setors.id', '=', 'congregacaos.setor_id')
            ->where('users.id', '>', 1)
            ->findOrFail($id);
        if($user->id !== 1) {
            $setores = Setor::orderBy("nome")->get();
            $permissoes = Permissao::where('id', '<>', 4)->get();

            return view('super-master.edit.user', compact(['user', 'setores', 'permissoes']));
        } else {
            return redirect()->back();
        }
    }

    public function updateUserSuperMaster(Request $request, $id) {
        $this->validate($request, [
            'status' => ['required', 'integer', 'min:0', 'max: 1']
        ], [
            'status.required' =>  'Status é obrigatório.',
            'status.integer' =>  'Esse Status não pode ser cadastrado.',
            'status.min' =>  'Esse Status não pode ser cadastrado.',
            'status.max' =>  'Esse Status não pode ser cadastrado.',
        ]);
        $user = User::findOrFail($id);
        if($user->id !== 1) {
            $user->congregacao_id = $request->congregacao;
            $user->permissao_id = $request->supermaster;
            $user->status = $request->status;
            $user->save();
            return redirect('/super-master/filters/users')->with('msg', 'Usuário atualizado com sucesso');
        } else {
            return redirect()->back();
        }

    }

    public function forceResetPassword($userId) : JsonResponse {
        $user = User::findOrFail($userId);
        if($user->id !== 1) {
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

    public function newCongregacao(Request $request) {
        $this->validate($request, [
           'setor' => ['required'],
           'congregacao' => ['required']
        ], [
            'setor.required' => 'Nome é obrigatório',
            'congregacao.required' => 'Congregação é obrigatória'
        ]);
        $congregacao = new Congregacao;
        $congregacao->setor_id = $request->setor;
        $congregacao->nome = $request->congregacao;
        $congregacao->save();

        return redirect()->back()->with('msg', 'Congregação cadastrada com sucesso');
    }

    public function newSala(Request $request) : RedirectResponse {
        $this->validate($request, [
            'select_congregacao' => ['required'],
            'input_tipo_sala' => ['required'],
            'input_nome_sala' => ['required']
        ], [
            'congregacao.required' => 'Congregação é obrigatória',
            'input_tipo_sala.required' => 'Tipo de sala é obrigatório',
            'input_nome_sala.required' => 'Nome da sala é obrigatório'
        ]);
        try {
            $sala = new Sala;
            $sala->congregacao_id = $request->select_congregacao;
            $sala->tipo = $request->input_tipo_sala;
            $sala->nome = $request->input_nome_sala;
            $sala ->hash = bin2hex(random_bytes(2));
            $sala->save();
            return redirect()->back()->with('msg', 'Sala cadastrada com sucesso');
        } catch (\Exception $e) {
            return redirect()->back()->with('msg', 'Erro ao cadastrar sala');
        }

    }

    public function congregacoesFilters(Request $req) {
        $congregacoes = Congregacao::select('congregacaos.*', 'setors.nome as setor_nome');
        if ($req->setor) {
            $congregacoes = $congregacoes->where('setor_id', '=', $req->setor);
        }
        if ($req->nome) {
            $congregacoes = $congregacoes->where('congregacaos.nome', 'like', '%' . $req->nome .'%');
        }
        $congregacoes = $congregacoes
            ->join('setors', 'setors.id', '=', 'congregacaos.setor_id')
            ->orderBy('nome')
            ->get();
        $setores = Setor::orderBy("nome")->get();
        return view('super-master.filters.congregacoes', compact(['congregacoes', 'setores']));
    }
    public function editCongregacao($id) {
        $setores = Setor::orderBy("nome")->get();
        $congregacao = Congregacao::select('congregacaos.nome as nome', 'congregacaos.*', 'setors.nome as setor_nome')
            ->join('setors', 'setors.id', '=', 'congregacaos.setor_id')
            ->findOrFail($id);

        return view ('super-master.edit.congregacao', compact(['congregacao', 'setores']));
    }

    public function updateCongregacao(Request $request, $id) {
        $this->validate($request, [
            'setor' => ['required'],
            'congregacao' => ['required']
        ], [
            'setor.required' => 'Nome é obrigatório',
            'congregacao.required' => 'Congregação é obrigatória'
        ]);
        $congregacao = Congregacao::findOrFail($id);
        $congregacao->setor_id = $request->setor;
        $congregacao->nome = $request->congregacao;
        $congregacao->save();

        return redirect('/super-master/filters/congregacoes')->with('msg', 'Congregação atualizada com sucesso');
    }


}
