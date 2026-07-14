<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;
use App\Models\User;

class CustomAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Session::has('user_id') && Cookie::has('remember_me_cookie')) {
            $token = Cookie::get('remember_me_cookie');
            $user = User::where('remember_token', $token)->first();

            if ($user) {
                Session::put('user_id', $user->id);
                Session::put('user_role', $user->role);
                Session::put('user_name', $user->name);
            }
        }

        return $next($request);
    }
}