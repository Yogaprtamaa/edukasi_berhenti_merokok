<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Professional;
use App\Models\ProfessionalVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfessionalController extends Controller
{
    public function index()
    {
        $professionals = Professional::with('user')
            ->latest()
            ->paginate(20);

        return view('admin.professionals.index', compact('professionals'));
    }

    public function show(Professional $professional)
    {
        $professional->load('user');
        return view('admin.professionals.show', compact('professional'));
    }

    public function edit(Professional $professional)
    {
        $professional->load('user');
        return view('admin.professionals.edit', compact('professional'));
    }

    public function update(Request $request, Professional $professional)
    {
        $data = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'email'          => ['required', 'email', 'unique:users,email,' . $professional->user_id],
            'type'           => ['required', 'in:dokter,psikolog,konselor,nutrisionis'],
            'specialization' => ['nullable', 'string', 'max:255'],
            'license_number' => ['nullable', 'string', 'max:100'],
            'hourly_rate'    => ['nullable', 'numeric', 'min:0'],
            'is_verified'    => ['boolean'],
        ]);

        $professional->user->update([
            'name'  => $data['name'],
            'email' => $data['email'],
        ]);

        $professional->update([
            'type'           => $data['type'],
            'specialization' => $data['specialization'],
            'license_number' => $data['license_number'],
            'hourly_rate'    => $data['hourly_rate'],
            'is_verified'    => $request->boolean('is_verified'),
            'verified_at'    => $request->boolean('is_verified') ? ($professional->verified_at ?? now()) : null,
        ]);

        return redirect()->route('admin.professionals')->with('success', 'Data profesional berhasil diperbarui.');
    }

    public function destroy(Professional $professional)
    {
        $professional->user->delete();
        return back()->with('success', 'Profesional berhasil dihapus.');
    }

    public function approve(Professional $professional)
    {
        $professional->update([
            'is_verified' => true,
            'verified_at' => now(),
        ]);

        ProfessionalVerification::updateOrCreate(
            ['professional_id' => $professional->id],
            [
                'admin_id'     => Auth::id(),
                'status'       => 'approved',
                'processed_at' => now(),
            ]
        );

        return back()->with('success', 'Profesional berhasil disetujui.');
    }

    public function reject(Request $request, Professional $professional)
    {
        $request->validate(['notes' => ['nullable', 'string']]);

        ProfessionalVerification::updateOrCreate(
            ['professional_id' => $professional->id],
            [
                'admin_id'     => Auth::id(),
                'status'       => 'rejected',
                'notes'        => $request->notes,
                'processed_at' => now(),
            ]
        );

        return back()->with('success', 'Pengajuan ditolak.');
    }
}
