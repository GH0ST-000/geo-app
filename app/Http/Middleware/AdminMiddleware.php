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
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated with web guard
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();

            // Check if user is admin
            if ($user->is_admin) {
                return $next($request);
            }

            // Redirect to dashboard with error message if not admin
            return redirect()->route('dashboard')->with('error', 'You do not have admin access.');
        }

        // Redirect to login page if not authenticated
        return redirect()->route('home')->with('error', 'Please login to access the admin area.');
    }
}
