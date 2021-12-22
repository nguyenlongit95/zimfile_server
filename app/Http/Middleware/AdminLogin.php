<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminLogin
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
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role == config('const.admin') || $user->role == config('const.sub_admin')) {
                return $next($request);
            } else {
                Auth::logout();
                return redirect('/admin/logout');
            }
        } else {
            return redirect('/admin/login')->with('status', 'Please login to admin');
        }
    }
}
