<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{public function login(Request $request)
    {

    
        // Captura as credenciais
        $credenciais = $request->only(['email', 'password']);
    
        // Tenta autenticar o usuário usando o guard 'api' (JWT)
        if (!$token = auth('api')->attempt($credenciais)) {
            return response()->json(['erro' => 'Usuário ou senha inválido!'], 403);
        }
    
        // Recupera o usuário autenticado
        $user = auth('api')->user();
        
        // Captura o IP atual do usuário
        $ip = $request->ip();
        
        // Verifica se já existe uma sessão ativa para esse usuário no mesmo IP
        $existingSession = DB::table('active_sessions')
            ->where('user_id', $user->id)
            ->where('ip_address', $ip)
            ->first();
    
        if ($existingSession) {
            // Se há uma sessão ativa para o mesmo IP, pede confirmação para encerrar
            return response()->json([
                'message' => 'Você já tem uma sessão ativa nesse dispositivo/IP.',
                'confirm_session_termination' => true, // Indica que o frontend deve solicitar confirmação
                'existing_ip' => $existingSession->ip_address
            ], 409); // HTTP 409 Conflict
        }
    
        // Se não há sessão no mesmo IP, continua com o login
        return $this->createSession($user, $ip, $token);
    }
    
    /**
     * Método auxiliar para criar ou atualizar a sessão do usuário.
     */
    protected function createSession($user, $ip, $token)
    {
        // Cria uma nova entrada de sessão ou atualiza a existente
        DB::table('active_sessions')->updateOrInsert(
            ['user_id' => $user->id, 'ip_address' => $ip],
            ['session_id' => session()->getId(), 'last_activity' => now()]
        );
    
        // Retorna o token JWT gerado e as informações do usuário
        return response()->json([
            'token' => $token,
            'user' => $user,
            'token_expiration' => config('jwt.ttl') * 60 // Expiração do token em segundos
        ]);
    }
    
    /**
     * Método para encerrar a sessão ativa anterior.
     */
    public function terminateSession(Request $request)
    {
        $user = auth('api')->user();
        $ip = $request->input('ip'); // IP da sessão a ser encerrada
    
        // Remove a sessão anterior com o IP fornecido
        $existingSession = DB::table('active_sessions')
            ->where('user_id', $user->id)
            ->where('ip_address', $ip)
            ->first();
    
        if ($existingSession) {
            session()->getHandler()->destroy($existingSession->session_id);
            DB::table('active_sessions')->where('id', $existingSession->id)->delete();
    
            return response()->json(['message' => 'Sessão anterior encerrada com sucesso.']);
        }
    
        return response()->json(['message' => 'Nenhuma sessão ativa encontrada para o IP fornecido.'], 404);
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