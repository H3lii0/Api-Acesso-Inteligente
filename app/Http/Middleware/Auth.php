<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class Auth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
    $ip = $request->ip();
    
    // Verifique se já há uma sessão ativa para esse IP
    $existingSession = DB::table('active_sessions')
        ->where('user_id', $user->id)
        ->where('ip_address', $ip)
        ->first();

    if ($existingSession) {
        // Sessão ativa para o mesmo IP existe
        return $next($request);
    }

    // Caso contrário, crie uma nova entrada de sessão
    DB::table('active_sessions')->insert([
        'user_id' => $user->id,
        'ip_address' => $ip,
        'session_id' => session()->getId(),
        'last_activity' => now()
    ]);
        
        return $next($request);
    }
}
