<?php

use Illuminate\Support\Facades\Route;
use App\Http\api\controllers\ApiController;
use App\Http\api\controllers\SalaRestController;
use App\Http\api\controllers\FuncaoRestController;
use App\Http\api\controllers\PessoaRestController;

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

Route::get('/salas/congregacao/{congregacaoId}', [SalaRestController::class, 'getSalasByCongregacao']);
Route::get('/funcaos', [FuncaoRestController::class, 'getFuncaos']);
Route::get('/pessoas_sala/{sala_id}', [PessoaRestController::class, 'getDataSala']);
Route::post('/pessoa/store/verify-duplicated', [PessoaRestController::class, 'verifyDuplicated'])->name('api.pessoa.store.verify-duplicated');
