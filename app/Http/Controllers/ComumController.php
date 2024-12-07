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
    private $chamadaDiaCongregacaoRepository;
    public function __construct(PessoaRepository $pessoaRepository, PresencaPessoaRepository $presencaPessoaRepository, ChamadaDiaCongregacaoRepository $chamadaDiaCongregacaoRepository)
    {
        $this->pessoaRepository = $pessoaRepository;
        $this->presencaPessoaRepository = $presencaPessoaRepository;
        $this->chamadaDiaCongregacaoRepository= $chamadaDiaCongregacaoRepository;
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

        $chamadaDiaBD = $this->chamadaDiaCongregacaoRepository->findChamadaDiaToday(auth()->user()->congregacao_id, date('Y-m-d'));
        if ($chamadaDiaBD) {
            $dateChamadaDia = $chamadaDiaBD->date;
        } else {
            $dateChamadaDia = null;
        }

        return view('comum.marcar-presenca', compact(['view', 'pessoaSalas', 'presente', 'dateChamadaDia']));
    }


    public function meusDados() : View {

        $pessoa = Pessoa::select('pessoas.*',
        'ufs.nome as uf_nome',
        'formations.nome as formation_nome', 'users.matricula as matricula')
        ->join('ufs', 'pessoas.id_uf', '=', 'ufs.id')
        ->join('formations', 'pessoas.id_formation', '=', 'formations.id')
        ->join('users', 'pessoas.id', '=', 'users.pessoa_id')
        ->findOrFail(auth()->user()->pessoa_id);

        $view = 'meus-dados';
        return view('comum.meus-dados', compact('view', 'pessoa'));
    }

    public function minhasPresencas(Request $request) : View {
        $month = date('m');
        $year = date('Y');
       if ($request->month && $request->year) {
            $month = $request->month;
            $year = $request->year;
        }

        $presencas = $this->presencaPessoaRepository->findByMonthAndYearAndPessoa($month, $year, auth()->user()->pessoa_id);
        $view = 'minhas-presencas';
        $mesesNome = [1 => 'Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
        return view('comum.minhas-presencas', compact('view', 'presencas', 'mesesNome', 'month', 'year'));
    }


}
