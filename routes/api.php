<?php

use App\Http\api\controllers\PreCadastroRestController;
use App\Http\api\controllers\StatesRestController;
use App\Http\api\controllers\EducationRestController;
use App\Http\api\controllers\PublicoRestController;
use Illuminate\Support\Facades\Route;
use App\Http\api\controllers\ApiController;
use App\Http\api\controllers\SalaRestController;
use App\Http\api\controllers\FuncaoRestController;
use App\Http\api\controllers\PessoaRestController;
use App\Http\api\controllers\ChamadaRestController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/pessoas', [ApiController::class, 'getPessoas']);
Route::get('/congregacoes/{id}', [ApiController::class, 'getCongregacoes']);

Route::get('/salas', [SalaRestController::class, 'getSalasByCongregacao']);
Route::get('/funcaos', [FuncaoRestController::class, 'getFuncaos']);
Route::get('/pessoas_sala/{sala_id}', [PessoaRestController::class, 'getDataSala']);
Route::get('/pessoa/{id}', [PessoaRestController::class, 'show']);
Route::post('/pessoa/store/verify-duplicated', [PessoaRestController::class, 'verifyDuplicated'])->name('api.pessoa.store.verify-duplicated');
Route::get('/aniversariantes', [PessoaRestController::class, 'getAniversariantes']);
Route::get('/pre-cadastros', [PreCadastroRestController::class, 'getList']);
Route::get('/pre-cadastros/{id}', [PreCadastroRestController::class, 'edit']);
Route::post('/pre-cadastros/approve', [PreCadastroRestController::class, 'approve']);
Route::delete('/pre-cadastros/destroy', [PreCadastroRestController::class, 'destroy']);
Route::put('/pre-cadastros/{id}', [PreCadastroRestController::class, 'update'])->name('update.preregister.api');


// Rotas de Estados (UFs)
Route::get('/estados', [StatesRestController::class, 'index']);
Route::get('/estados/{id}', [StatesRestController::class, 'show']);

// Rotas de Formações
Route::get('/formacoes', [EducationRestController::class, 'index']);
Route::get('/formacoes/{id}', [EducationRestController::class, 'show']);

// Rotas de Públicos
Route::get('/publicos', [PublicoRestController::class, 'index']);
Route::get('/publicos/{id}', [PublicoRestController::class, 'show']);

// Rotas de Chamadas
Route::get('/chamada/{id}', [ChamadaRestController::class, 'show']);

