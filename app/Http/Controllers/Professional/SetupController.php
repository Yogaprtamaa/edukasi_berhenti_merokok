<?php

namespace App\Http\Controllers\Professional;

use App\Http\Controllers\Controller;
use App\Models\Professional;
use App\Models\ProfessionalVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SetupController extends Controller
{
    public function show()
    {
        if (Auth::user()->professional) {
            return redirect()->route('professional.dashboard');
        }
        return view('professional.setup');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'type'           => ['required', 'in:psikolog,dokter'],
            'specialization' => ['required', 'string', 'max:100'],
            'license_number' => ['required', 'string', 'max:100'],
            'hourly_rate'    => ['required', 'numeric', 'min:0'],
        ]);

        $professional = Professional::create([
            'user_id'        => Auth::id(),
            'type'           => $data['type'],
            'specialization' => $data['specialization'],
            'license_number' => $data['license_number'],
            'hourly_rate'    => $data['hourly_rate'],
            'is_verified'    => false,
        ]);

        ProfessionalVerification::create([
            'professional_id' => $professional->id,
            'status'          => 'pending',
        ]);

        return redirect()->route('professional.dashboard')
            ->with('success', 'Data berhasil disimpan. Menunggu verifikasi admin.');
    }
}
