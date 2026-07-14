<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('public.signin');
    }

    public function showRegister()
    {
        return view('public.signup');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return redirect()->back()->withErrors(['login_error' => 'Invalid credentials pool matching records.'])->withInput();
        }

        Session::put('user_id', $user->id);
        Session::put('user_role', $user->role);
        Session::put('user_name', $user->name);

        if ($request->has('remember')) {
            $rememberToken = Str::random(60);
            $user->remember_token = $rememberToken;
            $user->save();
            
            Cookie::queue('remember_me_cookie', $rememberToken, 43200);
        }

        Session::flash('success', 'Logged in successfully!');

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('public.home');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user'
        ]);

        Session::put('user_id', $user->id);
        Session::put('user_role', $user->role);
        Session::put('user_name', $user->name);

        return redirect()->route('public.home')->with('success', 'Account registered successfully!');
    }

    public function logout()
    {
        if (Session::has('user_id')) {
            $user = User::find(Session::get('user_id'));
            if ($user) {
                $user->remember_token = null;
                $user->save();
            }
        }

        Session::forget(['user_id', 'user_role', 'user_name']);
        Cookie::queue(Cookie::forget('remember_me_cookie'));

        return redirect()->route('public.home')->with('success', 'Logged out smoothly.');
    }
}