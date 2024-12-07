<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function changePassword(Request $request)
    {
        $this->validate($request, [
            'password' => ['required', 'min:6', 'regex:/^.*(?=[^a-z]*[a-z])(?=\D*\d)(?=[^!@?]*[!@?]).*$/'],
        ], [
            'password.required' => 'A senha é obrigatória.',
            'password.min' => 'A senha precisa ter no mínimo 6 dígitos.',
            'password.regex' => 'A senha precisa conter, no mínimo, uma letra maiúscula, minúscula, um número e um caractere especial (@)'

        ]);
        $user = User::findOrFail(auth()->user()->id);
        $user->password = bcrypt($request->password);
        $user->save();

        return redirect()->back()->with('msg_success', 'Senha alterada com sucesso');
    }
}
