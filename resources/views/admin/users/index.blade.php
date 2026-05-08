@extends('layouts.app')

@section('title', 'Manajemen Pengguna')

@section('content')
    <header class="mb-10 md:mb-12">
        <div class="mb-4 flex items-center gap-3 sm:mb-5 sm:gap-4">
            <span class="h-px w-8 bg-[#1A1A1A] sm:w-12"></span>
            <span class="editorial-label">Admin</span>
        </div>
        <h1 class="page-title">Manajemen <span class="italic text-[#D4AF37]">Pengguna</span></h1>
        <p class="mt-4 max-w-xl text-sm leading-relaxed text-[#6C6863]">Kelola semua akun pengguna, profesional, dan admin di platform.</p>
    </header>

    <div class="card">
        @forelse($users as $user)
            <div class="grid gap-4 border-b border-[#1A1A1A]/10 py-5 last:border-0 sm:grid-cols-[1fr_auto] sm:items-center">
                <div class="flex items-center gap-4">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center border border-[#1A1A1A]/20 bg-[#EBE5DE]">
                        <span class="text-xs font-semibold text-[#1A1A1A]">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                    </div>
                    <div class="min-w-0">
                        <p class="truncate font-medium text-[#1A1A1A]">{{ $user->name }}</p>
                        <p class="text-sm text-[#6C6863]">{{ $user->email }}</p>
                    </div>
                    <span class="shrink-0 @if($user->role === 'admin') badge-red @elseif($user->role === 'professional') badge-blue @else badge-green @endif">
                        {{ ucfirst($user->role) }}
                    </span>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.users.edit', $user) }}" class="badge-yellow cursor-pointer">Edit</a>
                    @if($user->id !== auth()->id())
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Hapus pengguna {{ $user->name }}?')">
                            @csrf
                            @method('DELETE')
                            <button class="badge-red cursor-pointer">Hapus</button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="py-12 text-center text-sm text-[#6C6863]">Tidak ada pengguna.</div>
        @endforelse
    </div>

    <div class="mt-6">{{ $users->links() }}</div>
@endsection
