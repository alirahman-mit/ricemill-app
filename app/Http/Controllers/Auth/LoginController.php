<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login (fallback).
     */
    protected $redirectTo = '/petani/dashboard';

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * The user has been authenticated — redirect based on role.
     */
    protected function authenticated(Request $request, $user)
    {
        if ($user->role === 'petani') {
            return redirect('/petani/dashboard');
        } elseif ($user->role === 'rice_mill') {
            return redirect('/ricemill/dashboard');
        } elseif ($user->role === 'packager') {
            return redirect('/packager/dashboard');
        }

        return redirect('/home');
    }
}