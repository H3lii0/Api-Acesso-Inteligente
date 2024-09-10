<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Frequencia;
use Carbon\Carbon;


class AcessosController extends Controller
{
    public function index()
    {
        $dataAtual = Carbon::today();
        $totalHoje = Frequencia::whereDate('data_acesso', $dataAtual)->count();
        return response()->json(['total' => $totalHoje], 200);
    }
}
