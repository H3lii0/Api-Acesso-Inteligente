<?php

use App\Http\Controllers\AcessosController;
use App\Http\Controllers\AlunoController;
use App\Http\Controllers\CoordenacaoController;
use App\Http\Controllers\FrequenciaController;
use App\Http\Controllers\ResponsavelController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->middleware('jwt.auth')->group( function() {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh',[AuthController::class, 'refresh']);
    Route::get('me',[AuthController::class, 'me']);
    Route::apiResource('coordenacao', CoordenacaoController::class);
    Route::apiResource('responsavel', ResponsavelController::class);
    // Route::apiResource('frequencia', FrequenciaController::class);
});
Route::apiResource('aluno', AlunoController::class);
Route::apiResource('frequencia', FrequenciaController::class);
Route::apiResource('total-acessos', AcessosController::class);
Route::post('/registrar-acesso', [FrequenciaController::class, 'registrarAcesso']);
Route::post('aluno/{id}/validar-senha', [AlunoController::class, 'validarSenha']);
Route::post('login', [AuthController::class, 'login']);
Route::post('login', [AuthController::class, 'terminateSession']);