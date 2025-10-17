<?php

use App\Http\Controllers\ChamadaAdminController;
use App\Http\Controllers\ComumController;
use App\Http\Controllers\PreCadastroController;
use App\Http\Controllers\PresencaPessoaController;
use App\Http\Controllers\UserController;
use App\Models\PresencaPessoa;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClasseController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\SuperMasterController;
use App\Http\Controllers\ChamadaController;
use App\Http\Controllers\PessoaController;
use App\Http\Controllers\RelatorioController;
use Carbon\Carbon;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('/', [AuthController::class, 'logar']);

Route::post('/logout', [AuthController::class, 'logout']);
Route::get('/forgot-password', [AuthController::class, 'forgotPassword']);

Route::get('/cadastro/{congregacaoId}', [\App\Http\Controllers\PessoaController::class, 'indexCadastroGeral']);
Route::post('/cadastro-geral', [PreCadastroController::class, 'store'])->name('cadastro.pessoa.geral');

Route::middleware(['auth'])->group(function() {
    Route::get('/inicio', [AuthController::class, 'inicio'])->name('inicio');
    Route::get('/sobre', function () { return view('/about'); });
    Route::post('/baixar-relatorio-presenca-classe',[\App\Http\Controllers\PresencaPessoaController::class, 'getPresencasOfClasse'])->name('relatorios.presenca-classe-post');
    Route::post('/realizar-chamada', [ChamadaController::class, 'realizarChamada']);
    Route::post('/user/change-password', [UserController::class, 'changePassword']);
    Route::get('/reset-password', function() {
        return view('/reset-password');
    })->name('password.reset');
    Route::post('/post/reset-password', [UserController::class, 'resetPassword']);
});

Route::middleware(['auth', 'classe', 'status', 'resetPassword'])->group(function () {
    Route::get('/classe', [ClasseController::class, 'indexClasse']);
    Route::get('/classe/cadastro-pessoa', [PessoaController::class, 'indexCadastroClasse']);
    Route::post('/classe/cadastro-pessoa', [PreCadastroController::class, 'store'])->name('cadastro.pessoa.classe');
    Route::get('/classe/pessoas', [ClasseController::class, 'searchPessoaClasse']);
    Route::post('/classe/pessoas', [ClasseController::class, 'searchPessoaClasse']);
    Route::get('/classe/visualizar-pessoa/{id}', [ClasseController::class, 'showPessoaClasse']);
    Route::get('/classe/chamada-dia', [ClasseController::class, 'indexChamadaClasse']);
    Route::get('/classe/todas-chamadas', [ClasseController::class, 'searchChamadaClasse']);
    Route::post('/classe/todas-chamadas', [ClasseController::class, 'searchChamadaClasse']);
    Route::get('/classe/visualizar-chamada/{id}', [ChamadaController::class, 'showChamadaClasse']);
    Route::get('/classe/aniversariantes', [ClasseController::class, 'searchAniversariantes']);
    Route::post('/classe/aniversariantes', [ClasseController::class, 'searchAniversariantes']);
    Route::get('/classe/pdf-chamada/{id}', [ChamadaController::class, 'generatePdfToChamadasToClasse']);

});

Route::middleware(['auth', 'master', 'status', 'resetPassword'])->group(function () {
    Route::get('/master', [MasterController::class, 'dashboardMaster']);
    Route::get('/master/cadastro/usuario', [MasterController::class, 'indexUsuarioMaster']);
    Route::get('/master/filtro/usuario', [MasterController::class, 'searchUserMaster']);
    Route::post('/master/filtro/usuario', [MasterController::class, 'searchUserMaster']);
    Route::get('/master/edit/usuario/{id}', [MasterController::class, 'editUserMaster']);
    Route::put('/master/update/usuario/{id}', [MasterController::class, 'updateUserMaster']);
    Route::put('/master/update/reset-password/{userId}', [MasterController::class, 'forceResetPassword']);

    Route::get('/master/cadastro/classe', [MasterController::class, 'indexSalaMaster']);
    Route::post('/master/cadastro/classe', [MasterController::class, 'storeSalaMaster']);
    Route::get('/master/filtro/classe', [MasterController::class, 'searchSalaMaster']);
    Route::post('/master/filtro/classe', [MasterController::class, 'searchSalaMaster']);
    Route::get('/master/edit/classe/{id}', [MasterController::class, 'editSalaMaster']);
    Route::put('/master/update/classe/{id}', [MasterController::class, 'updateSalaMaster']);
//    Route::delete('/master/filtro/classe/{id}', [MasterController::class, 'destroySalaMaster']);

    Route::post('/master/liberar-chamada', [ChamadaController::class, 'liberarChamada']);
    Route::post('/master/liberar-cadastro', [PessoaController::class, 'liberarLinkCadastroGeral']);
    Route::delete('/master/apagar-dia-chamada/{id}', [ChamadaController::class, 'apagarChamadaDia']);
    Route::get('/master/chamadas-dia', [ChamadaController::class, 'chamadasLiberadaMes']);

    Route::get('/master/configuracoes/pessoas', [MasterController::class, 'indexConfiguracoesPessoas']);
    Route::get('/master/configuracoes/congregacao', [MasterController::class, 'indexConfiguracoesCongregacao']);

    Route::post('/congregacao/salvar-localizacao', [MasterController::class, 'salvarLocalizacao']);
    Route::delete('/delete-pessoa/{id}', [PessoaController::class, 'delete']);


});

Route::middleware(['auth', 'admin', 'status', 'resetPassword'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index']);
    Route::get('/admin/aniversariantes', [AdminController::class, 'indexAniversariantes']);
    Route::post('/admin/aniversariantes', [AdminController::class, 'searchAniversariantes']);
    Route::get('/admin/sobre', [AdminController::class, 'sobre']);


    Route::get('/admin/cadastro/pessoa', [PessoaController::class, 'indexCadastroAdmin']);
    Route::post('/admin/cadastro/pessoa', [PessoaController::class, 'store'])->name('cadastro.pessoa.admin');
    Route::get('/admin/filtro/pessoa', [AdminController::class, 'showFilterPessoa']);
    Route::post('/filter-pessoa', [PessoaController::class, 'search']);
    Route::get('/admin/visualizar/pessoa/{id}', [AdminController::class, 'showPessoa']);
    Route::get('/admin/edit/pessoa/{id}', [AdminController::class, 'editPessoa']);
    Route::put('/admin/update/pessoa/{id}', [PessoaController::class, 'update']);

    Route::get('/admin/filtro/pre-cadastros', [PreCadastroController::class, 'list']);
    Route::post('/admin/filtro/pre-cadastros', [PreCadastroController::class, 'list']);
    Route::post('/admin/approve/pre-cadastro/{id}', [PreCadastroController::class, 'approve']);
    Route::get('/admin/edit/pre-cadastro/{id}', [PreCadastroController::class, 'edit']);
    Route::put('/admin/update/pre-cadastro/{id}', [PreCadastroController::class, 'update']);
    Route::delete('/admin/remove/pre-cadastro/{id}', [PreCadastroController::class, 'destroy']);

    Route::get('/admin/financeiro/geral', [AdminController::class, 'indexFinanceiroGeral']);
    Route::get('/admin/financeiro/filtro', [AdminController::class, 'searchFinanceiro']);
    Route::post('/admin/financeiro/filtro', [AdminController::class, 'searchFinanceiro']);
    Route::get('/admin/financeiro/entrada', [AdminController::class, 'indexFinanceiroEntrada']);
    Route::post('/admin/financeiro/entrada', [AdminController::class, 'storeFinanceiroEntrada']);
    Route::get('/admin/financeiro/saida', [AdminController::class, 'indexFinanceiroSaida']);
    Route::post('/admin/financeiro/saida', [AdminController::class, 'storeFinanceiroSaida']);
    Route::get('/admin/financeiro/visualizar/{id}', [AdminController::class, 'showFinanceiroTransacao']);
    Route::get('/admin/financeiro/editar/{id}', [AdminController::class, 'editFinanceiroTransacao']);
    Route::put('/admin/financeiro/update/{id}', [AdminController::class, 'updateFinanceiroTransacao']);


    Route::get('/admin/cadastro/aviso', [AdminController::class, 'indexAviso']);
    Route::post('/admin/cadastro/aviso', [AdminController::class, 'storeAviso']);
    Route::get('/admin/filtro/aviso', [AdminController::class, 'searchAviso']);
    Route::post('/admin/filtro/aviso', [AdminController::class, 'searchAviso']);
    Route::get('/admin/edit/aviso/{id}', [AdminController::class, 'editAviso']);
    Route::put('/admin/update/aviso/{id}', [AdminController::class, 'updateAviso']);
    Route::delete('/admin/filtro/aviso/{id}', [AdminController::class, 'destroyAviso']);

    Route::get('/admin/realizar-chamadas', [ChamadaAdminController::class, 'indexRealizarChamadas']);
    Route::get('/admin/chamadas', [AdminController::class, 'searchChamadas']);
    Route::post('/admin/chamadas', [AdminController::class, 'searchChamadas']);
    Route::get('/admin/visualizar/chamada/{id}', [ChamadaController::class, 'showChamada']);
    Route::get('/admin/visualizar/pdf-chamada/{id}', [ChamadaAdminController::class, 'generatePdfToChamadasToAdmin']);
    Route::get('/admin/visualizar/pdf-folha-frequencia/{id}/{date}', [ChamadaAdminController::class, 'printFolhaFrequencia']);
    Route::get('/admin/relatorios/cadastro', [AdminController::class, 'indexRelatorioToday']);
    Route::post('/admin/relatorios/cadastro', [AdminController::class, 'storeRelatorioToday']);
    Route::get('/admin/relatorios/todos', [RelatorioController::class, 'gerarRelatorio']);
    Route::post('/admin/relatorios/todos', [RelatorioController::class, 'gerarRelatorio']);
    Route::get('/admin/visualizar/relatorio/{date}', [RelatorioController::class, 'show']);
    Route::get('/admin/visualizar/pdf-relatorio/{date}', [RelatorioController::class, 'generatePdfRelatorioChamada']);

    Route::get('/admin/relatorios/presenca-classe',[\App\Http\Controllers\PresencaPessoaController::class, 'showRelatorioPresenca'])->name('relatorios.presenca-classe');




});

Route::middleware(['auth', 'supermaster', 'status', 'resetPassword'])->group(function () {
    Route::get('/super-master/', [SuperMasterController::class, 'index']);
    Route::post('/super-master/cadastro/usuario', [AuthController::class, 'storeUsuarioSuperMaster']);
    Route::get('/super-master/filters/users', [SuperMasterController::class, 'userFilters']);
    Route::post('/super-master/filters/users', [SuperMasterController::class, 'userFilters']);
    Route::get('/super-master/edit/user/{id}', [SuperMasterController::class, 'editUserSuperMaster']);
    Route::put('/super-master/update/user/{id}', [SuperMasterController::class, 'updateUserSuperMaster']);
    Route::put('/super-master/reset-password/user/{userId}', [SuperMasterController::class, 'forceResetPassword']);
    Route::post('/super-master/new/congregacao', [SuperMasterController::class, 'newCongregacao']);
    Route::get('/super-master/filters/congregacoes', [SuperMasterController::class, 'congregacoesFilters']);
    Route::post('/super-master/filters/congregacoes', [SuperMasterController::class, 'congregacoesFilters']);
    Route::get('/super-master/edit/congregacao/{id}', [SuperMasterController::class, 'editCongregacao']);
    Route::put('/super-master/update/congregacao/{id}', [SuperMasterController::class, 'updateCongregacao']);

    Route::post('/super-master/new/sala', [SuperMasterController::class, 'newSala']);
});

Route::middleware(['auth', 'comum', 'status', 'resetPassword'])->group(function () {
    Route::get('/comum', [ComumController::class, 'index']);
    Route::get('/comum/marcar-presenca', [ComumController::class, 'indexMarcarPresenca']);
    Route::post('/comum/marcar-presenca', [PresencaPessoaController::class, 'marcarPresencaIndividualNivelComum']);
    Route::get('/comum/meus-dados', [ComumController::class, 'meusDados']);
    Route::get('/comum/minhas-presencas', [ComumController::class, 'minhasPresencas']);
    Route::post('/comum/minhas-presencas', [ComumController::class, 'minhasPresencas']);
});

Route::get('/teste-email', function() {
    return view('emails.pessoaCadastrada', ['pessoaNome' => 'Chrystian', 'congregacaoNome' => 'Tempolo Sede']);
});

//verificar se idade está compatível com a classe. Se não, sugerir classe correta de acordo com a idade
Route::get('/planilha-transferencias', function() {

    //TODO: preencher collection com todas as salas
    $salasIdades = collect([
       ['id' => 15, 'nome' => 'Pequeninos de Jesus', 'idadeMinima' => 3, 'idadeMaxima' => 4],
       ['id' => 16, 'nome' => 'Débora', 'idadeMinima' => 5, 'idadeMaxima' => 6],
       ['id' => 17, 'nome' => 'Amiguinhos de Jesus', 'idadeMinima' => 7, 'idadeMaxima' => 8],
       ['id' => 18, 'nome' => 'Leão de Judá', 'idadeMinima' => 9, 'idadeMaxima' => 10],
       ['id' => 9, 'nome' => 'El-Shaddai', 'idadeMinima' => 11, 'idadeMaxima' => 12],
       ['id' => 3, 'nome' => 'Maranata', 'idadeMinima' => 18, 'idadeMaxima' => 24],
       ['id' => 12, 'nome' => 'Gileade', 'idadeMinima' => 15, 'idadeMaxima' => 17],
       ['id' => 13, 'nome' => 'Shekinah', 'idadeMinima' => 13, 'idadeMaxima' => 14]
    ]);


    $alunos = DB::table('pessoa_salas')
        ->join('pessoas', 'pessoas.id', '=', 'pessoa_salas.pessoa_id')
        ->join('salas', 'salas.id', '=', 'pessoa_salas.sala_id')
        ->where('pessoa_salas.funcao_id', 1)
        ->where('pessoas.situacao', 1)
        ->where('pessoas.deleted_at', null)
        ->whereIn('pessoa_salas.sala_id', $salasIdades->pluck('id'))
        ->select('pessoas.id', 'pessoas.nome', 'data_nasc', 'salas.nome as classe', 'pessoa_salas.sala_id as sala_id')
        ->get();

    $arrayTransferencias = [];
    foreach ($alunos as $aluno) {
        foreach ($salasIdades as $sala) {
            $idade = floor((strtotime(Carbon::now()) - strtotime($aluno->data_nasc))/(60 * 60 * 24) /365.25);
            if ($idade <= $sala['idadeMaxima'] && $idade >= $sala['idadeMinima']) {
                if ($aluno->sala_id == $sala['id']) {
                    break;
                } else {
                    $arrayTransferencias[] = [
                        'id' => $aluno->id,
                        'nome' => $aluno->nome,
                        'idade' => $idade,
                        'classe_atual' => $aluno->classe,
                        'classe_recomendada' => $sala['nome']." (Faixa Etária: ".$sala['idadeMinima']." - ".$sala['idadeMaxima'].")",
                    ];
                }
            }
        }
    }

    return response()->json($arrayTransferencias);

});

Route::get('/planilha-inativos', function() {
    $dataInicio = "2025-04-06";
    $dataFim = "2025-09-28";

    $salasId = [3,4,5,8,9,10,11,12,13,14,15,16,17,18,19,20,36,175];

    $inativosPorSala = [];

    for ($i = 0; $i < count($salasId); $i++) {
        $presencaPessoa = PresencaPessoa::select('pessoa_id',
            'pessoas.nome as pessoa_nome',
            'funcaos.nome as funcao_nome',
            'pessoas.telefone',
            DB::raw('sum(presente) as presencas'),
            DB::raw('(case when sum(presente) <= 12 and sum(presente) > 0 then "INATIVAR" ELSE "EXCLUIR" end) as acao')
        )
            ->join('pessoas', 'pessoas.id', '=', 'presenca_pessoas.pessoa_id')
            ->join('funcaos', 'funcaos.id', '=', 'presenca_pessoas.funcao_id')
            ->join('salas', 'salas.id', '=', 'presenca_pessoas.sala_id')
            ->having('presencas', '<=', 12)
            ->whereBetween('presenca_pessoas.created_at',  [$dataInicio, $dataFim])
            ->where('presenca_pessoas.funcao_id', 1)
            ->where('presenca_pessoas.sala_id', $salasId[$i])
            ->where('pessoas.deleted_at', null)
            ->where('pessoas.situacao', 1)
            ->where('presenca_pessoas.sala_id', '<>', 8)
            ->orderBy('presencas', 'desc')
            ->groupBy('pessoa_id')
            ->get();

        $inativosPorSala[] = [
            'sala' => \App\Models\Sala::findOrFail($salasId[$i])->nome,
            'quantidade'=> $presencaPessoa->count(),
            'alunos' => $presencaPessoa
        ];

    }

    return response()->json($inativosPorSala);
});




