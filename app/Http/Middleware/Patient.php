<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Patient
{
    public function handle(Request $request, Closure $next): Response
    {
        // Match exactly how Doctor middleware works
        if(Auth::user()->role == 'patient'){ // Use == instead of ===
            return $next($request);
        }
        abort(403);
    }
}