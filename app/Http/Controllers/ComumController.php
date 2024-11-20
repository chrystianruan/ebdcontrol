<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Pessoa;

class ComumController extends Controller
{
    public function index() : View {
        $view = 'dashboard';
        return view('comum.index', compact('view'));
    }

    public function meusDados() : View {

        $pessoa = Pessoa::findOrFail(auth()->user()->pessoa_id);

        $view = 'meus-dados';
        return view('comum.meus-dados', compact('view', 'pessoa'));
    }
    
}
