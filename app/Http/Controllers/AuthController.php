<?php

namespace App\Http\Controllers;

use App\Models\Congregacao;
use App\Models\Permissao;
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
            'password' => ['required', 'min:6']
        ], [
            'username.required' =>  'Nome de usuário é obrigatório.',
            'username.min' => 'O nome de usuário precisa ter no mínimo 6 dígitos.',
            'password.required' =>  'Senha é obrigatória.',
            'password.min' => 'A senha precisa ter no mínimo 6 dígitos.'
        ]);

        if(Auth::attempt(['matricula' => $request->username, 'password' => $request->password])) {
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
