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
            if(auth()->check() AND $user->permissao_id !== 4) {
                return redirect()->route('inicio')->with('danger', 'Você não tem permissão!');
            }
        return $next($request);

    }
}
