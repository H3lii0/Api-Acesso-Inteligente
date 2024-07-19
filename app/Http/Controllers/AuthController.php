<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request){

        $credenciais = $request->all(['email', 'password']);
        
        $token = auth('api')->attempt($credenciais);

        if($token){
            return Response()->json(['token' => $token]);
        } else {
            return Response()->json(['erro' => 'Usuário ou senha inválido!'], 403);
        }
        
        return $token;
    }

}
