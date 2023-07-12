<?php

namespace App\Http\Controllers;

use App\Models\Congregacao;
use App\Models\Pessoa;
use App\Models\Sala;
use App\Models\Setor;
use App\Models\User;
use Illuminate\Http\Request;

class SuperMasterController extends Controller
{
    public function index() {
        $setores = Setor::orderBy('id', 'asc')->get();
        return view('super-master.dashboard', compact(['setores']));
    }

    public function userFilters(Request $request) {
        $setores = Setor::orderBy("nome")
            ->get();
        $users = User::select('users.*', 'congregacaos.nome as nome_congregacao', 'setors.nome as nome_setor')
            ->where('id_nivel', '=', 1);
        if ($request->congregacao) {
            $users = $users->where('congregacao_id', '=', $request->congregacao);
        }
        if ($request->nome) {
            $users = $users->where("name", "like",'%'.$request->nome.'%');
        }
        if ($request->supermaster) {
            $users = $users->where("super_master", '=', $request->supermaster);
        }
        if ($request->status) {
            $users = $users->where("status", '=', $request->status);
        }
        $users = $users->join("congregacaos", 'congregacaos.id', '=', 'users.congregacao_id')
            ->join("setors", 'setors.id', '=', 'congregacaos.setor_id')
            ->orderBy("name")
            ->get();

        return view('super-master.filters.users', compact(['users', 'setores']));

    }

    public function editUserSuperMaster($id) {
        $user = User::select('users.*', 'congregacaos.nome as congregacao_nome', 'setors.nome as setor_nome', 'setors.id as setor_id')
            ->join("congregacaos", 'congregacaos.id', '=', 'users.congregacao_id')
            ->join("setors", 'setors.id', '=', 'congregacaos.setor_id')
            ->findOrFail($id);
        $setores = Setor::orderBy("nome")->get();

        return view('super-master.edit.user', compact(['user', 'setores']));
    }

    public function updateUserSuperMaster(Request $request, $id) {
        $this->validate($request, [
            'nome' => ['required'],
            'username' => ['required'],
            'status' => ['required', 'integer', 'min:0', 'max: 1']
        ], [
            'nome.required' => 'Nome é obrigatório',
            'username.required' => 'Nome de usuário é obrigatório',
            'status.required' =>  'Status é obrigatório.',
            'status.integer' =>  'Esse Status não pode ser cadastrado.',
            'status.min' =>  'Esse Status não pode ser cadastrado.',
            'status.max' =>  'Esse Status não pode ser cadastrado.',

        ]);
        $user = User::findOrFail($id);
        $user->name = $request->nome;
        $user->username = $request->username;
        $user->congregacao_id = $request->congregacao;
        $user->super_master = $request->supermaster;
        $user->status = $request->status;
        $user->save();

        return redirect('/super-master/filters/users')->with('msg', 'Usuário atualizado com sucesso');
    }

    public function editPasswordUserSuperMaster($id) {
        $user = User::findOrFail($id);
        return view('super-master.edit.password-user')->with(compact(['user']));
    }
    public function updatePasswordUserSuperMaster(Request $request, $id) {
        $this->validate($request, [
            'password' => ['required', 'min:8', 'regex:/^.*(?=[^a-z]*[a-z])(?=\D*\d)(?=[^!@?]*[!@?]).*$/'],
        ], [
            'password.required' => 'A senha é obrigatória.',
            'password.min' => 'A senha precisa ter no mínimo 8 dígitos.',
            'password.regex' => 'A senha precisa conter, no mínimo, uma letra maiúscula, minúscula, um número e um caractere especial (@)'

        ]);
        $user = User::findOrFail($id);
        $user->password = bcrypt($request->password);
        $user->save();

        return redirect('/super-master/filters/users')->with('msg', 'Senha de usuário atualizado com sucesso');
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

    public function congregacoesFilters(Request $req) {
        $congregacoes = Congregacao::select('congregacaos.*', 'setors.nome as setor_nome');
        if ($req->setor) {
            $congregacoes = $congregacoes->where('setor_id', '=', $req->setor);
        }
        if ($req->nome) {
            $congregacoes = $congregacoes->where('nome', 'like', '%' . $req->nome, '%');
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
