<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class checkLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
{
    if (Session::has('userID')) {
        $user = Auth::user();
        if (!$user) {
            Log::debug('User not authenticated');
            return redirect()->route('signin'); // or handle unauthenticated case
        }
        
        Log::debug('Authenticated User: ', ['user' => $user]);

        $redirectRoutes = [
            'signin' => 'adminDashboard',
            'register' => 'adminDashboard',
        ];
        if (array_key_exists($request->route()->getName(), $redirectRoutes)) {
            return redirect()->route($redirectRoutes[$request->route()->getName()]);
        }
        if ($user->jobtitle === 0) { // Admin
            return redirect()->route('adminDashboard');
        } elseif ($user->jobtitle === 1) { // Helper
            return redirect()->route('userDashboard');
        } elseif ($user->jobtitle === 2) { // Staff
            return redirect()->route('userDashboard');
        }
    }
    return $next($request);
}



}
