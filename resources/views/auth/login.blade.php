@extends('layouts.guest')

@section('title', 'Masuk - BerhentiMerokok')

@section('content')
    <h1 class="mb-2 text-4xl leading-none text-[#1A1A1A]">Selamat datang kembali</h1>
    <p class="mb-8 text-sm leading-relaxed text-[#6C6863]">Masuk untuk melanjutkan perjalananmu</p>

    @if($errors->any())
        <div class="mb-5 border border-red-700/40 bg-red-700/5 px-4 py-3 text-sm text-red-800">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('login') }}" method="POST" class="space-y-5">
        @csrf

        <div>
            <label class="editorial-label mb-1 block">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required class="input-field" placeholder="email@contoh.com">
        </div>

        <div>
            <label class="editorial-label mb-1 block">Password</label>
            <input type="password" name="password" required class="input-field" placeholder="Masukkan password">
        </div>

        <label class="flex items-center gap-2 text-sm text-[#6C6863]">
            <input type="checkbox" name="remember" class="border-[#1A1A1A] text-[#1A1A1A]">
            Ingat saya
        </label>

        <button type="submit" class="btn-primary w-full text-center"><span>Masuk</span></button>
    </form>

    <p class="mt-8 text-center text-sm text-[#6C6863]">
        Belum punya akun?
        <a href="{{ route('register') }}" class="font-medium text-[#1A1A1A] transition-colors duration-500 hover:text-[#D4AF37]">Daftar sekarang</a>
    </p>
@endsection
