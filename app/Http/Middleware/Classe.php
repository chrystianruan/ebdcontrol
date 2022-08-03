<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Classe
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        $user = auth()->user();
            if(auth()->check() AND ($user->id_nivel === 1 OR $user->id_nivel === 2)) {
                return redirect()->route('inicio')->with('danger', 'Seu usuário não é de Secretário/Classe ou Professor!');
            }
        return $next($request);

    }
}
