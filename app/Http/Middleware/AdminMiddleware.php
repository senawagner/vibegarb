<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar se o usuário está logado
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Você precisa estar logado para acessar esta área.');
        }

        // Verificar se o usuário é admin (email específico ou campo role)
        $user = auth()->user();
        
        // Pode ser por email específico ou campo 'role' no banco
        $isAdmin = $user->email === 'admin@vibegarb.com' || 
                   (isset($user->role) && $user->role === 'admin');

        if (!$isAdmin) {
            return redirect()->route('home')->with('error', 'Acesso negado. Área restrita para administradores.');
        }

        return $next($request);
    }
} 