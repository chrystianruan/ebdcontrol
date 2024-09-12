<?php

use App\Http\Controllers\ChamadaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\ApiController;
use App\Http\Controllers\api\SalaController;
use App\Http\Controllers\api\FuncaoController;

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
