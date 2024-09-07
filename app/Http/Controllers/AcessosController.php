<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Frequencia;


class AcessosController extends Controller
{
    public function index()
    {
        $total = Frequencia::count();
        return response()->json(['total' => $total], 200);
    }
}
