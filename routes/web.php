<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClasseController;
use App\Http\Controllers\MasterController;


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

Route::get('/first-user', [AuthController::class, 'indexFirstUser']);


Route::post('/logout', [AuthController::class, 'logout']);
Route::get('/forgot-password', [AuthController::class, 'forgotPassword']);

Route::get('/inicio', [AuthController::class, 'inicio'])->name('inicio')->middleware(['auth']);

Route::middleware(['auth', 'classe', 'status'])->group(function () {
    Route::get('/classe', [ClasseController::class, 'indexClasse']);
    Route::get('/classe/cadastro-pessoa', [ClasseController::class, 'indexCadastroClasse']);
    Route::post('/classe/cadastro-pessoa', [ClasseController::class, 'storePessoaClasse']);
    Route::get('/classe/pessoas', [ClasseController::class, 'searchPessoaClasse']);
    Route::post('/classe/pessoas', [ClasseController::class, 'searchPessoaClasse']);
    Route::get('/classe/visualizar-pessoa/{id}', [ClasseController::class, 'showPessoaClasse']);
    Route::get('/classe/chamada-dia', [ClasseController::class, 'indexChamadaClasse']);
    Route::post('/classe/chamada-dia', [ClasseController::class, 'storechamadaClasse']);
    Route::get('/classe/todas-chamadas', [ClasseController::class, 'searchChamadaClasse']);
    Route::post('/classe/todas-chamadas', [ClasseController::class, 'searchChamadaClasse']);
    Route::get('/classe/visualizar-chamada/{id}', [ClasseController::class, 'showChamadaClasse']);
    Route::get('/classe/aniversariantes', [ClasseController::class, 'searchAniversariantes']);
    Route::post('/classe/aniversariantes', [ClasseController::class, 'searchAniversariantes']);
    Route::get('/classe/pdf-chamada/{id}', [ClasseController::class, 'generatePdfToChamadas']);
    
});

Route::middleware(['auth', 'master', 'status'])->group(function () {
    Route::get('/master', [MasterController::class, 'dashboardMaster']);
    Route::get('/master/cadastro/usuario', [AuthController::class, 'indexUsuarioMaster']);
    Route::post('/master/cadastro/usuario', [AuthController::class, 'storeUsuarioMaster']);
    Route::get('/master/filtro/usuario', [AuthController::class, 'searchUserMaster']);
    Route::post('/master/filtro/usuario', [AuthController::class, 'searchUserMaster']);
    Route::get('/master/edit/usuario/{id}', [AuthController::class, 'editUserMaster']);
    Route::put('/master/update/usuario/{id}', [AuthController::class, 'updateUserMaster']);
    Route::get('/master/edit/usuario-senha/{id}', [AuthController::class, 'editUserPassword']);
    Route::put('/master/update/usuario-senha/{id}', [AuthController::class, 'updateUserPassword']);

    Route::get('/master/cadastro/classe', [MasterController::class, 'indexSalaMaster']);
    Route::post('/master/cadastro/classe', [MasterController::class, 'storeSalaMaster']);
    Route::get('/master/filtro/classe', [MasterController::class, 'searchSalaMaster']);
    Route::post('/master/filtro/classe', [MasterController::class, 'searchSalaMaster']);
    Route::get('/master/edit/classe/{id}', [MasterController::class, 'editSalaMaster']);
    Route::put('/master/update/classe/{id}', [MasterController::class, 'updateSalaMaster']);
    Route::delete('/master/filtro/classe/{id}', [MasterController::class, 'destroySalaMaster']);

    
});

Route::middleware(['auth', 'admin', 'status'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index']);
    Route::get('/admin/aniversariantes', [AdminController::class, 'searchAniversariantes']);
    Route::post('/admin/aniversariantes', [AdminController::class, 'searchAniversariantes']);
    Route::get('/admin/sobre', [AdminController::class, 'sobre']);

    
    Route::get('/admin/cadastro/pessoa', [AdminController::class, 'indexPessoa']);
    Route::post('/admin/cadastro/pessoa', [AdminController::class, 'storePessoa']);
    Route::get('/admin/filtro/pessoa', [AdminController::class, 'showFilterPessoa']);
    Route::post('/admin/filtro/pessoa', [AdminController::class, 'searchPessoa']);
    Route::get('/admin/visualizar/pessoa/{id}', [AdminController::class, 'showPessoa']);
    Route::get('/admin/edit/pessoa/{id}', [AdminController::class, 'editPessoa']);
    Route::put('/admin/update/pessoa/{id}', [AdminController::class, 'updatePessoa']);
    Route::delete('/admin/filtro/pessoa/{id}', [AdminController::class, 'destroyPessoa']);

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
    

    

    Route::get('/admin/chamadas', [AdminController::class, 'searchChamadas']);
    Route::post('/admin/chamadas', [AdminController::class, 'searchChamadas']);
    Route::get('/admin/visualizar/chamada/{id}', [AdminController::class, 'showChamada']);
    Route::get('/admin/visualizar/pdf-chamada/{id}', [AdminController::class, 'generatePdfToChamadas']);

    Route::get('/admin/relatorios/cadastro', [AdminController::class, 'indexRelatorioToday']);
    Route::post('/admin/relatorios/cadastro', [AdminController::class, 'storeRelatorioToday']);
    Route::get('/admin/relatorios/todos', [AdminController::class, 'searchRelatorios']);
    Route::post('/admin/relatorios/todos', [AdminController::class, 'searchRelatorios']);
    Route::get('/admin/visualizar/relatorio/{id}', [AdminController::class, 'showRelatorio']);
    Route::get('/admin/visualizar/pdf-relatorio/{id}', [AdminController::class, 'generatePdfToRelatorios']);


});




