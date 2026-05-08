<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name'       => ['required', 'string', 'max:100'],
            'birth_date' => ['nullable', 'date'],
            'password'   => ['nullable', 'min:8', 'confirmed'],
        ]);

        $user->name       = $data['name'];
        $user->birth_date = $data['birth_date'] ?? $user->birth_date;

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
