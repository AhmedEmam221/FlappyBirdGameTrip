<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class Admin
{
    /**
     * Handle the incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|string)  $next
     * @return \Illuminate\Http\Response|string
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated
        if (! $request->user()) {
            return redirect()->route('login')
                ->with('error', 'Please log in to access the admin dashboard.');
        }

        // Check if user has admin role
        if (! $request->user()->isAdmin()) {
            return redirect()->route('home')
                ->with('error', 'Access denied. Admin privileges required.');
        }

        return $next($request);
    }
}