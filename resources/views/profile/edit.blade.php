@extends('layouts.app')

@section('title', 'Edit Profil')

@section('content')
    <div class="mx-auto max-w-lg">
        <div class="mb-10 border-b border-[#1A1A1A]/10 pb-8">
            <span class="editorial-label">Akun</span>
            <h1 class="mt-2 page-title">Edit Profil</h1>
        </div>

        <div class="card">
            <div class="mb-6 flex items-center gap-5 border-b border-[#1A1A1A]/10 pb-6">
                <div class="flex h-14 w-14 flex-shrink-0 items-center justify-center border border-[#1A1A1A]/20 bg-[#EBE5DE]">
                    <span class="font-serif text-2xl text-[#1A1A1A]">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                </div>
                <div>
                    <p class="font-serif text-lg text-[#1A1A1A]">{{ $user->name }}</p>
                    <p class="text-sm text-[#6C6863]">{{ $user->email }}</p>
                    <span class="badge-green mt-1">{{ ucfirst($user->role) }}</span>
                </div>
            </div>

            <form action="{{ route('profile.update') }}" method="POST" class="space-y-5">
                @csrf @method('PUT')

                <div>
                    <label class="editorial-label mb-2 block">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="input-field">
                    @error('name')<p class="mt-1 text-xs text-red-700">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="editorial-label mb-2 block">Email</label>
                    <input type="email" value="{{ $user->email }}" disabled class="input-field opacity-40">
                    <p class="mt-1 text-xs text-[#6C6863]">Email tidak dapat diubah</p>
                </div>

                <div>
                    <label class="editorial-label mb-2 block">Tanggal Lahir</label>
                    <input type="date" name="birth_date" value="{{ old('birth_date', $user->birth_date) }}" class="input-field">
                </div>

                <div class="border-t border-[#1A1A1A]/10 pt-5">
                    <label class="editorial-label mb-2 block">Password Baru (opsional)</label>
                    <input type="password" name="password" class="input-field" placeholder="Kosongkan jika tidak ingin ganti">
                </div>

                <div>
                    <label class="editorial-label mb-2 block">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" class="input-field" placeholder="Ulangi password baru">
                    @error('password')<p class="mt-1 text-xs text-red-700">{{ $message }}</p>@enderror
                </div>

                <button type="submit" class="btn-primary w-full"><span>Simpan Perubahan</span></button>
            </form>
        </div>
    </div>
@endsection
