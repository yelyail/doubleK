<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class userAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $jobtitles)
    {
        if (Auth::check()) {
            $allowedJobtitles = explode(',', $jobtitles); // Split into an array

            // Log for debugging
            Log::info('Allowed job titles: ', $allowedJobtitles);
            Log::info('User job title: ', ['jobtitle' => Auth::user()->jobtitle]);

            if (in_array(Auth::user()->jobtitle, $allowedJobtitles)) {
                return $next($request);
            }
        }

        return redirect()->back()->with('error', "You don't have permission to access this page.");
    }


}