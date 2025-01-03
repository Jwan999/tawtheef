<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            if ($request->is('dashboard')) {
                return redirect('/login');
            }
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            return redirect('/login');
        }

        if (Auth::user()->role !== 'admin') {
            if ($request->is('dashboard')) {
                return redirect('/')->with('error', 'Access denied. Admin privileges required.');
            }
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Access denied. Admin privileges required.'], 403);
            }
            return redirect('/')->with('error', 'Access denied. Admin privileges required.');
        }

        return $next($request);
    }
}
