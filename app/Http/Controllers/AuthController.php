<?php

namespace App\Http\Controllers;

use App\Models\Congregacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Sala;
use App\Models\User;
use PHPUnit\Exception;

class AuthController extends Controller
{
    public function logar(Request $request) {

        $this->validate($request, [
            'username' => ['required', 'min:6'],
            'password' => ['required', 'min:8']
        ], [
            'username.required' =>  'Nome de usuário é obrigatório.',
            'username.min' => 'O nome de usuário precisa ter no mínimo 6 dígitos.',
            'password.required' =>  'Senha é obrigatória.',
            'password.min' => 'A senha precisa ter no mínimo 8 dígitos.'
        ]);

        if(Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            return redirect('/inicio');
        } else {
            return redirect()->back()->with('danger', 'Nome de usuário ou senha inválida');
        }
    }




    public function index() {
        return view('welcome');
    }


    public function inicio() {
        return view('/inicio');
    }

    public function about() {
        return view("/about");
    }

    public function indexUsuarioMaster() {

        $dataAtual = date('d/m/Y');
        $niveis = Sala::where('congregacao_id', '=', auth()->user()->congregacao_id)
            ->orderBy('nome')
            ->get();
        return view('/master/cadastro/usuario', ['niveis' => $niveis, 'dataAtual' => $dataAtual]);
    }

    public function storeUsuarioMaster(Request $request) {
        $lastNivel = Sala::where('congregacao_id', '=', auth()->user()->congregacao_id)
            ->orderBy('id', 'desc')
            ->first();
        $this->validate($request, [
            'name' => ['required'],
            'username' => ['required', 'min:6', 'unique:users,username'],
            'id_nivel' => ['required', 'integer', 'min:1', 'max:'.$lastNivel -> id],
            'password' => ['required', 'min:8', 'regex:/^.*(?=[^a-z]*[a-z])(?=\D*\d)(?=[^!@?]*[!@?]).*$/'],
            'status' => ['integer', 'min:0', 'max:0'],
        ], [
            'name.required' => 'Nome é obrigatório.',
            'username.required' =>  'Nome de usuário é obrigatório.',
            'username.min' => 'O nome de usuário precisa ter no mínimo 6 dígitos.',
            'username.unique' => 'Esse nome de usuário já está sendo usado.',
            'status.integer' => 'Status só pode ser cadastrado como ativo',
            'status.min' => 'Status só pode ser cadastrado como ativo',
            'status.max' => 'Status só pode ser cadastrado como ativo',
            'id_nivel.required' =>  'Nível é obrigatório.',
            'id_nivel.min' =>  'Esse nível não pode ser cadastrado MÍNIMO.',
            'id_nivel.max' =>  'Esse nível não pode ser cadastrado MÁXIMO',
            'password.required' => 'A senha é obrigatória.',
            'password.min' => 'A senha precisa ter no mínimo 8 dígitos.',
            'password.regex' => 'A senha precisa conter, no mínimo, uma letra maiúscula, minúscula, um número e um caractere especial (@)'
        ]);

        $user = new User;
        $user -> name = $request->name;
        $user -> username = $request->username;
        $user -> password = bcrypt($request->password);
        $user -> id_nivel = $request->id_nivel;
        $user -> congregacao_id = auth()->user()->congregacao_id;
        $user -> status = 0;
        $user -> save();

        return redirect('/master/cadastro/usuario')->with('msg', 'Usuário cadastrado com sucesso');
    }

    public function searchUserMaster(Request $request) {
        $nome = request('nome');
        $nivel = request('nivel');
        $status = request('status');
        $niveis = Sala::where('congregacao_id', '=', auth()->user()->congregacao_id)
            ->orderBy('nome')
            ->get();
        //nome
        if(isset($request->nome) && empty($request->nivel) && empty($request -> status)) {
            $users = User::where([['name', 'like', '%'.$request -> nome.'%']])
                ->where('congregacao_id', '=', auth()->user()->congregacao_id)
                ->get();

        }
         //nivel
        elseif(empty($request->nome) && isset($request->nivel) && empty($request -> status)) {
            $users = User::where('id_nivel', '=', $request->nivel)
                ->where('congregacao_id', '=', auth()->user()->congregacao_id)
                ->get();

        }
        //status
        elseif(empty($request->nome) && empty($request->nivel) && isset($request -> status)) {
            $users = User::where('status', $request->status)
                ->where('congregacao_id', '=', auth()->user()->congregacao_id)
                ->get();

        }
        //nivel e status
        elseif(empty($request->nome) && isset($request->nivel) && isset($request -> status)) {
            $users = User::where('id_nivel', '=', $request->nivel)
            ->where('congregacao_id', '=', auth()->user()->congregacao_id)
            ->where('status', $request->status)
            ->get();

        }
         else {
            $users = User::all();
        }

        return view('/master/filtro/usuario', ['niveis' => $niveis, 'users' => $users, 'nome' => $nome,
        'status' => $status, 'nivel' => $nivel]);

    }

    public function editUserMaster($id) {
        $user = User::findOrFail($id);
        $niveis = Sala::where('congregacao_id', '=', auth()->user()->congregacao_id)
            ->orderBy('nome')
            ->get();

        return view('/master/edit/usuario', ['user' => $user, 'niveis' => $niveis]);
    }

    public function updateUserMaster(Request $request) {
        $lastNivel = Sala::where('congregacao_id', '=', auth()->user()->congregacao_id)
            ->orderBy('id', 'desc')
            ->first();
        $this->validate($request, [
            'id_nivel' => ['required', 'integer', 'min:1', 'max:'.$lastNivel -> id],
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

            User::findOrFail($request->id)->update([
                'id_nivel' => $request->id_nivel,
                'status' => $request->status
            ]);
            return redirect('/master/filtro/usuario')->with('msg', 'Usuário atualizado com sucesso.');


    }

    public function editUserPassword($id) {
        $user = User::findOrFail($id);
        return view('/master/edit/usuario-senha', ['user' => $user]);
    }

    public function updateUserPassword(Request $request) {
        $this->validate($request, [
            'password' => ['required', 'min:8', 'regex:/^.*(?=[^a-z]*[a-z])(?=\D*\d)(?=[^!@?]*[!@?]).*$/'],
        ], [
            'password.required' => 'A senha é obrigatória.',
            'password.min' => 'A senha precisa ter no mínimo 8 dígitos.',
            'password.regex' => 'A senha precisa conter, no mínimo, uma letra maiúscula, minúscula, um número e um caractere especial (@)'

        ]);

        if(isset($request->id_nivel) || isset($request->name) || isset($request->username) || isset($request->status)) {
            return redirect('/master/filtro/usuario')->with('msg2', 'Seu usuário não tem permissão');
        } else {
            User::findOrFail($request->id)->update(['password' => bcrypt($request->password)]);
            return redirect('/master/filtro/usuario')->with('msg', 'Senha de Usuário atualizada com sucesso.');
        }


    }

    public function storeUsuarioSuperMaster(Request $request) {
        $lastCongregacao = Congregacao::orderBy('id', 'desc')->first();
        $this->validate($request, [
            'name' => ['required'],
            'username' => ['required', 'min:6', 'unique:users,username'],
            'password' => ['required', 'min:8', 'regex:/^.*(?=[^a-z]*[a-z])(?=\D*\d)(?=[^!@?]*[!@?]).*$/'],
            'status' => ['integer', 'min:0', 'max:0'],
            'congregacao' => ['required', 'integer', 'min:1', 'max:'.$lastCongregacao -> id]
        ], [
            'name.required' => 'Nome é obrigatório.',
            'username.required' =>  'Nome de usuário é obrigatório.',
            'username.min' => 'O nome de usuário precisa ter no mínimo 6 dígitos.',
            'username.unique' => 'Esse nome de usuário já está sendo usado.',
            'status.integer' => 'Status só pode ser cadastrado como ativo',
            'status.min' => 'Status só pode ser cadastrado como ativo',
            'status.max' => 'Status só pode ser cadastrado como ativo',
            'congregacao.required' =>  'Congregação é obrigatória.',
            'congregacao.min' =>  'Congregação não pode ser cadastrada MÍNIMO.',
            'congregacao.max' =>  'Congregação não pode ser cadastrada MÁXIMO',
            'password.required' => 'A senha é obrigatória.',
            'password.min' => 'A senha precisa ter no mínimo 8 dígitos.',
            'password.regex' => 'A senha precisa conter, no mínimo, uma letra maiúscula, minúscula, um número e um caractere especial (@)'
        ]);

        $user = new User;
        $user -> name = $request->name;
        $user -> username = $request->username;
        $user -> password = bcrypt($request->password);
        $user -> id_nivel = 1;
        $user -> congregacao_id = $request->congregacao;
        if ($request->super_master) {
          $user->super_master = true;
        } else {
          $user->super_master = false;
        }
        $user -> status = 0;
        $user -> save();


        return redirect()->back()->with('msg', 'Usuário Master cadastrado com sucesso');
    }

    public function logout(Request $request) {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');

    }

    public function forgotPassword() {
        return view('/forgot-password');
    }




}
