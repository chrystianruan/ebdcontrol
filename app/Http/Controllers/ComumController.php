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

    public function minhasPresencas() : View {
        $pessoa = Pessoa::findOrFail(auth()->user()->pessoa_id);
        $view = 'minhas-presencas';
        return view('comum.minhas-presencas', compact('view', 'pessoa'));
    }

}
