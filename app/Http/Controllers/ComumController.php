<?php

namespace App\Http\Controllers;

use App\Http\Repositories\PessoaRepository;
use App\Http\Repositories\PessoaSalaRepository;
use App\Models\Pessoa;
use App\Models\PessoaSala;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ComumController extends Controller
{
    private $pessoaRepository;
    public function __construct(PessoaRepository $pessoaRepository)
    {
        $this->pessoaRepository = $pessoaRepository;
    }
    public function index() : View {
        $view = 'dashboard';
        return view('comum.index', compact('view'));
    }

    public function indexMarcarPresenca() {
        $view = 'marcar-presenca';
        $pessoaSalas = $this->pessoaRepository->getSalasOfPessoa(auth()->user()->pessoa_id);
        return view('comum.marcar-presenca', compact(['view', 'pessoaSalas']));
    }

}
