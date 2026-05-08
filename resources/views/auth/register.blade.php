@extends('layouts.guest')

@section('title', 'Daftar — BerhentiMerokok')

@section('content')
    <h1 class="mb-2 text-4xl leading-none text-[#1A1A1A]">Buat akun baru</h1>
    <p class="mb-8 text-sm leading-relaxed text-[#6C6863]">Mulai perjalanan berhenti merokokmu hari ini</p>

    @if($errors->any())
        <div class="mb-5 border border-red-700/40 bg-red-700/5 px-4 py-3 text-sm text-red-800">
            <ul class="space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('register') }}" method="POST" class="space-y-5">
        @csrf

        <div>
            <label class="editorial-label mb-1 block">Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name') }}" required
                class="input-field" placeholder="Nama lengkap kamu">
        </div>

        <div>
            <label class="editorial-label mb-1 block">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required
                class="input-field" placeholder="email@contoh.com">
        </div>

        <div>
            <label class="editorial-label mb-1 block">Tanggal Lahir</label>
            <input type="date" name="birth_date" value="{{ old('birth_date') }}"
                class="input-field">
        </div>

        <div>
            <label class="editorial-label mb-1 block">Daftar sebagai</label>
            <select name="role" class="input-field">
                <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>Pengguna — Ingin Berhenti Merokok</option>
                <option value="professional" {{ old('role') === 'professional' ? 'selected' : '' }}>Profesional — Dokter / Psikolog</option>
            </select>
        </div>

        <div>
            <label class="editorial-label mb-1 block">Password</label>
            <input type="password" name="password" required
                class="input-field" placeholder="Minimal 8 karakter">
        </div>

        <div>
            <label class="editorial-label mb-1 block">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" required
                class="input-field" placeholder="Ulangi password">
        </div>

        <button type="submit" class="btn-primary w-full text-center"><span>Buat Akun</span></button>
    </form>

    <p class="mt-8 text-center text-sm text-[#6C6863]">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="font-medium text-[#1A1A1A] transition-colors duration-500 hover:text-[#D4AF37]">Masuk di sini</a>
    </p>
@endsection
