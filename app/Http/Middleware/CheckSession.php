<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!session()->has('logged_in')) {
            return redirect()->route('signin')->with('error', 'Please input email and password.');
        }

        if (!session()->has('access_token')) {
            session()->flush(); 
            return redirect()->route('signin')->with('error', 'Session expired');
        }

        return $next($request);
    }
}
