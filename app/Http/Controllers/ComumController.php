<?php

namespace App\Http\Controllers;

use App\Http\Repositories\ChamadaDiaCongregacaoRepository;
use App\Http\Repositories\PessoaRepository;
use App\Http\Repositories\PessoaSalaRepository;
use App\Http\Repositories\PresencaPessoaRepository;
use App\Models\Pessoa;
use App\Models\PessoaSala;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ComumController extends Controller
{
    private $pessoaRepository;
    private $presencaPessoaRepository;
    private $chamadaDiaRepository;
    public function __construct(PessoaRepository $pessoaRepository, PresencaPessoaRepository $presencaPessoaRepository, ChamadaDiaCongregacaoRepository $chamadaDiaRepository)
    {
        $this->pessoaRepository = $pessoaRepository;
        $this->presencaPessoaRepository = $presencaPessoaRepository;
        $this->chamadaDiaRepository = $chamadaDiaRepository;
    }
    public function index() : View {
        $view = 'dashboard';
        return view('comum.index', compact('view'));
    }

    public function indexMarcarPresenca() {
        $view = 'marcar-presenca';
        $presente = false;
        if ($this->presencaPessoaRepository->findByPessoaIdAndToday(auth()->user()->pessoa_id)) {
            if ($this->presencaPessoaRepository->findByPessoaIdAndToday(auth()->user()->pessoa_id)->presente) {
                $presente = true;
            }
        }
        $pessoaSalas = $this->pessoaRepository->getSalasOfPessoa(auth()->user()->pessoa_id);
        $dateChamadaDia= $this->chamadaDiaRepository->findChamadaDiaToday(auth()->user()->congregacao_id, date('Y-m-d'));
        return view('comum.marcar-presenca', compact(['view', 'pessoaSalas', 'presente', 'dateChamadaDia']));
    }

}
