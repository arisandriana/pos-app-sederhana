<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Menampilkan form login.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Proses login pengguna.
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'Email' => 'required|email',
            'KataSandi' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except('KataSandi'));
        }

        $credentials = [
            'Email' => $request->Email,
            'password' => $request->KataSandi, // Laravel expects 'password'
        ];

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'));
        }

        return redirect()->back()
            ->withInput($request->except('KataSandi'))
            ->withErrors(['Email' => 'Kredensial yang diberikan tidak cocok dengan catatan kami.']);
    }

    /**
     * Proses logout pengguna.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}