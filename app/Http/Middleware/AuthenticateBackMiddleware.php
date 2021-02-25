<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthenticateBackMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->session()->has('user_token') && !empty($request->session()->get('user_token'))) {
            return $next($request);
        } else {
            return redirect()->to('/login')->withErrors(['error' => 'É necessário login para acessar a aplicação']);
        }
    }
}
