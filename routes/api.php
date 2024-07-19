<?php

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

Route::apiResource('aluno', AlunoController::class);
Route::apiResource('coordenacao', CoordenacaoController::class);
Route::apiResource('responsavel', ResponsavelController::class);
Route::apiResource('frequencia', FrequenciaController::class);
Route::post('aluno/{id}/validar-senha', [AlunoController::class, 'validarSenha']);

Route::post('login', [AuthController::class, 'login']);