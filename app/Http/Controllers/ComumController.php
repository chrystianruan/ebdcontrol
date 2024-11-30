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

        $pessoa = Pessoa::select('pessoas.*', 
        'ufs.nome as uf_nome', 
        'formations.nome as formation_nome')
        ->join('ufs', 'pessoas.id_uf', '=', 'ufs.id')
        ->join('formations', 'pessoas.id_formation', '=', 'formations.id')
        /* ->join('usuarios_externos', 'pessoas.id', '=', 'usuarios_externos.pessoa_id') */
        ->where('pessoas.id', auth()->user()->pessoa_id)
        ->firstOrFail();

        $view = 'meus-dados';
        return view('comum.meus-dados', compact('view', 'pessoa'));
    }
    
}
