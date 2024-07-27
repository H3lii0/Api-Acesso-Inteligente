<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request){

        $credenciais = $request->only(['email', 'password']);

        if(!$token = auth('api')->attempt($credenciais)){
            return Response()->json(['erro' => 'Usuário ou senha inválido!'], 403);
        }
        
        return Response()->json(['token' => $token]);
    }

    public function logout()
    {
        auth('api')->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        $token = auth('api')->refresh();
        return response()->json(['token' => $token]);
    }

    public function me()
    {
        return response()->json(auth('api')->user());
    }
}