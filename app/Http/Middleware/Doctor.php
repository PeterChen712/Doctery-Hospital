<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class Doctor
{
    public function handle(Request $request, Closure $next): Response
    {
        if(Auth::user()->role == 'doctor'){
            return $next($request);
        }
        abort(403);
    }
}