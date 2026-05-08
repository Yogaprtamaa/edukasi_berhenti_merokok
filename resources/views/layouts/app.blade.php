<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Edukasi Berhenti Merokok')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#F9F8F6]">
    <div class="paper-grain"></div>
    <div class="grid-line left-[8%]"></div>
    <div class="grid-line left-1/3"></div>
    <div class="grid-line left-2/3"></div>
    <div class="grid-line right-[8%]"></div>

    @auth
        @php
            $homeRoute = route('home');

            $navItems = match (auth()->user()->role) {
                'admin' => [
                    ['label' => 'Dashboard', 'url' => route('admin.dashboard'), 'active' => request()->routeIs('admin.dashboard')],
                    ['label' => 'Users', 'url' => route('admin.users'), 'active' => request()->routeIs('admin.users*')],
                    ['label' => 'Profesional', 'url' => route('admin.professionals'), 'active' => request()->routeIs('admin.professionals*')],
                    ['label' => 'Konten', 'url' => route('admin.contents'), 'active' => request()->routeIs('admin.contents*')],
                    ['label' => 'Forum', 'url' => route('admin.forums'), 'active' => request()->routeIs('admin.forums*')],
                ],
                'professional' => [
                    ['label' => 'Dashboard', 'url' => route('professional.dashboard'), 'active' => request()->routeIs('professional.dashboard')],
                    ['label' => 'Janji Temu', 'url' => route('professional.appointments'), 'active' => request()->routeIs('professional.appointments')],
                    ['label' => 'Jadwal', 'url' => route('professional.schedule'), 'active' => request()->routeIs('professional.schedule')],
                ],
                default => [
                    ['label' => 'Progress', 'url' => route('user.progress'), 'active' => request()->routeIs('user.progress')],
                    ['label' => 'Edukasi', 'url' => route('contents.index'), 'active' => request()->routeIs('contents.*')],
                    ['label' => 'Buku', 'url' => route('books.index'), 'active' => request()->routeIs('books.*')],
                    ['label' => 'Konsultasi', 'url' => route('consultations.index'), 'active' => request()->routeIs('consultations.*')],
                    ['label' => 'Forum', 'url' => route('forums.index'), 'active' => request()->routeIs('forums.*')],
                ],
            };
        @endphp
    @else
        @php
            $homeRoute = url('/');
            $navItems = [];
        @endphp
    @endauth

    <nav class="sticky top-0 z-50 border-b border-[#1A1A1A]/10 bg-[#F9F8F6]/95 backdrop-blur" x-data="{ mobileOpen: false, userOpen: false }">
        <div class="mx-auto max-w-[1600px] px-5 sm:px-8 md:px-16">
            <div class="flex min-h-16 items-center justify-between gap-4 md:min-h-20">
                <a href="{{ $homeRoute }}" class="flex min-w-0 items-center gap-3">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center border border-[#1A1A1A]">
                        <svg class="h-5 w-5 text-[#1A1A1A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </div>
                    <span class="truncate font-serif text-lg text-[#1A1A1A] sm:text-xl">BerhentiMerokok</span>
                </a>

                <div class="hidden items-center gap-6 lg:flex">
                    @foreach($navItems as $item)
                        <a href="{{ $item['url'] }}" class="editorial-link {{ $item['active'] ? 'text-[#D4AF37]' : '' }}">{{ $item['label'] }}</a>
                    @endforeach
                </div>

                <div class="flex items-center gap-2">
                    @auth
                        @if(auth()->user()->role !== 'admin')
                            <a href="{{ route('notifications.index') }}" class="hidden border border-transparent p-2 text-[#6C6863] transition-colors duration-500 hover:border-[#1A1A1A]/20 hover:text-[#D4AF37] sm:block">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                            </a>
                        @endif

                        <button type="button" @click="mobileOpen = !mobileOpen" class="border border-[#1A1A1A]/20 px-3 py-2 text-[11px] font-medium uppercase tracking-[0.16em] text-[#1A1A1A] lg:hidden">
                            Menu
                        </button>

                        <div class="relative hidden sm:block">
                            <button @click="userOpen = !userOpen" class="flex items-center gap-2 border border-transparent p-2 text-sm font-medium text-[#1A1A1A] transition-colors duration-500 hover:border-[#1A1A1A]/20 hover:text-[#D4AF37]">
                                <div class="flex h-8 w-8 items-center justify-center border border-[#1A1A1A]/20 bg-[#EBE5DE]">
                                    <span class="text-xs font-semibold text-[#1A1A1A]">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                </div>
                                <span class="hidden max-w-32 truncate md:block">{{ auth()->user()->name }}</span>
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="userOpen" @click.away="userOpen = false" x-cloak class="absolute right-0 z-50 mt-2 w-56 border border-[#1A1A1A]/10 bg-[#F9F8F6] py-2 shadow-[0_8px_32px_rgba(0,0,0,0.12)]">
                                <a href="{{ route('home') }}" class="block px-4 py-3 text-xs font-medium uppercase tracking-[0.2em] text-[#1A1A1A] transition-colors duration-500 hover:text-[#D4AF37]">Beranda</a>
                                @if(auth()->user()->role === 'user')
                                    <a href="{{ route('user.progress') }}" class="block px-4 py-3 text-xs font-medium uppercase tracking-[0.2em] text-[#1A1A1A] transition-colors duration-500 hover:text-[#D4AF37]">Progress</a>
                                @endif
                                @if(auth()->user()->role !== 'admin')
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-3 text-xs font-medium uppercase tracking-[0.2em] text-[#1A1A1A] transition-colors duration-500 hover:text-[#D4AF37]">Profil Saya</a>
                                @endif
                                <hr class="my-1 border-[#1A1A1A]/10">
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full px-4 py-3 text-left text-xs font-medium uppercase tracking-[0.2em] text-red-700 hover:bg-red-700/5">Keluar</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn-secondary">Masuk</a>
                        <a href="{{ route('register') }}" class="btn-primary"><span>Daftar</span></a>
                    @endauth
                </div>
            </div>

            @auth
                <div x-show="mobileOpen" x-cloak class="border-t border-[#1A1A1A]/10 py-4 lg:hidden">
                    <div class="grid gap-2">
                        @foreach($navItems as $item)
                            <a href="{{ $item['url'] }}" class="border border-[#1A1A1A]/10 px-4 py-3 text-xs font-medium uppercase tracking-[0.18em] text-[#1A1A1A] {{ $item['active'] ? 'border-[#D4AF37] text-[#D4AF37]' : '' }}">{{ $item['label'] }}</a>
                        @endforeach
                        <a href="{{ route('home') }}" class="border border-[#1A1A1A]/10 px-4 py-3 text-xs font-medium uppercase tracking-[0.18em] text-[#1A1A1A]">Beranda</a>
                        @if(auth()->user()->role !== 'admin')
                            <a href="{{ route('profile.edit') }}" class="border border-[#1A1A1A]/10 px-4 py-3 text-xs font-medium uppercase tracking-[0.18em] text-[#1A1A1A]">Profil Saya</a>
                        @endif
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full border border-red-700/40 px-4 py-3 text-left text-xs font-medium uppercase tracking-[0.18em] text-red-700">Keluar</button>
                        </form>
                    </div>
                </div>
            @endauth
        </div>
    </nav>

    @if(session('success'))
        <div class="mx-auto max-w-[1600px] px-5 pt-5 sm:px-8 md:px-16">
            <div class="flex items-start gap-3 border border-[#D4AF37] bg-[#D4AF37]/10 px-4 py-3 text-sm leading-relaxed text-[#1A1A1A]">
                <svg class="mt-0.5 h-5 w-5 shrink-0 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 13l4 4L19 7"/>
                </svg>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mx-auto max-w-[1600px] px-5 pt-5 sm:px-8 md:px-16">
            <div class="flex items-start gap-3 border border-red-700/40 bg-red-700/5 px-4 py-3 text-sm leading-relaxed text-red-800">
                <svg class="mt-0.5 h-5 w-5 shrink-0 text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                {{ session('error') }}
            </div>
        </div>
    @endif

    <main class="mx-auto max-w-[1600px] px-5 py-8 sm:px-8 sm:py-10 md:px-16 md:py-16">
        @yield('content')
    </main>

    <footer class="mt-10 border-t border-[#1A1A1A]/10 bg-[#1A1A1A] md:mt-16">
        <div class="mx-auto max-w-[1600px] px-5 py-8 text-center text-[10px] uppercase tracking-[0.2em] text-[#F9F8F6]/70 sm:px-8 md:px-16">
            &copy; {{ date('Y') }} Platform Edukasi Berhenti Merokok - Universitas Paramadina
        </div>
    </footer>

    @stack('scripts')
    <script src="//unpkg.com/alpinejs" defer></script>
</body>
</html>
