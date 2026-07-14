<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Session::has('user_id')) {
            return redirect()->route('login')->withErrors(['auth' => 'Please sign in to proceed.']);
        }

        if (Session::get('user_role') !== 'admin') {
            abort(403, 'Unauthorized access action forbidden.');
        }

        return $next($request);
    }
}