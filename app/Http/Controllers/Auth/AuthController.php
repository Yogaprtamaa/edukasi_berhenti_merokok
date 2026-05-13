<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return $this->redirectByRole();
        }

        return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'                  => ['required', 'string', 'max:100'],
            'email'                 => ['required', 'email', 'max:150', 'unique:users'],
            'birth_date'            => ['nullable', 'date'],
            'role'                  => ['required', 'in:user,professional'],
            'password'              => ['required', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name'       => $data['name'],
            'email'      => $data['email'],
            'birth_date' => $data['birth_date'] ?? null,
            'role'       => $data['role'],
            'password'   => Hash::make($data['password']),
        ]);

        Auth::login($user);

        if ($user->role === 'professional') {
            return redirect()->route('professional.setup')
                ->with('success', 'Akun berhasil dibuat. Lengkapi data profesional Anda.');
        }

        return redirect()->route('home')
            ->with('success', 'Selamat datang, ' . $user->name . '!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    private function redirectByRole()
    {
        return redirect()->route('home');
    }
}
