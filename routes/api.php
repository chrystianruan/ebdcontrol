<?php

use App\Http\api\controllers\ApiController;
use App\Http\api\controllers\FuncaoController;
use App\Http\api\controllers\SalaController;
use Illuminate\Support\Facades\Route;
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

Route::get('/salas/congregacao/{congregacaoId}', [SalaController::class, 'getSalasByCongregacao']);
Route::get('/funcaos', [FuncaoController::class, 'getFuncaos']);

Route::get('/getChamadas/{periodoInicial}/{periodoFinal}', [ApiController::class, 'getChamadas']);
