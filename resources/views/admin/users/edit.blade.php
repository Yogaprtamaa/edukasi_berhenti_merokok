@extends('layouts.app')

@section('title', 'Edit Pengguna')

@section('content')
    <div class="mx-auto max-w-xl">
        <div class="mb-10 border-b border-[#1A1A1A]/10 pb-8">
            <a href="{{ route('admin.users') }}" class="editorial-label text-[#6C6863] hover:text-[#1A1A1A]">← Kembali</a>
            <h1 class="mt-4 page-title">Edit <span class="italic text-[#D4AF37]">Pengguna</span></h1>
            <p class="mt-2 text-sm text-[#6C6863]">{{ $user->email }}</p>
        </div>

        <div class="card">
            <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label class="editorial-label mb-2 block">Nama</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="input-field">
                    @error('name')<p class="mt-1 text-xs text-red-700">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="editorial-label mb-2 block">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="input-field">
                    @error('email')<p class="mt-1 text-xs text-red-700">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="editorial-label mb-2 block">Role</label>
                    <select name="role" class="input-field">
                        <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>User</option>
                        <option value="professional" {{ old('role', $user->role) === 'professional' ? 'selected' : '' }}>Professional</option>
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>

                <div>
                    <label class="editorial-label mb-2 block">Password Baru <span class="normal-case tracking-normal text-[#6C6863]">(kosongkan jika tidak diubah)</span></label>
                    <input type="password" name="password" class="input-field" placeholder="Min. 8 karakter">
                    @error('password')<p class="mt-1 text-xs text-red-700">{{ $message }}</p>@enderror
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit" class="btn-primary"><span>Simpan Perubahan</span></button>
                    <a href="{{ route('admin.users') }}" class="btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
