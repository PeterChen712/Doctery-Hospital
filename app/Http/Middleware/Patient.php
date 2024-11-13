<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class Patient
{
    public function handle(Request $request, Closure $next): Response
    {
        if(Auth::user()->role == 'patient'){
            return $next($request);
        }
        abort(403);
    }
}