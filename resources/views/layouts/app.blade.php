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
                    ['label' => 'Buku', 'url' => route('admin.books'), 'active' => request()->routeIs('admin.books*')],
                    ['label' => 'Pembayaran', 'url' => route('admin.payments'), 'active' => request()->routeIs('admin.payments*')],
                    ['label' => 'Transaksi Buku', 'url' => route('admin.orders'), 'active' => request()->routeIs('admin.orders*')],
                    ['label' => 'Appointment', 'url' => route('admin.appointments'), 'active' => request()->routeIs('admin.appointments*')],
                ],
                'professional' => [
                    ['label' => 'Dashboard', 'url' => route('professional.dashboard'), 'active' => request()->routeIs('professional.dashboard')],
                    ['label' => 'Janji Temu', 'url' => route('professional.appointments'), 'active' => request()->routeIs('professional.appointments')],
                    ['label' => 'Jadwal', 'url' => route('professional.schedule'), 'active' => request()->routeIs('professional.schedule')],
                ],
                default => [
                    ['label' => 'Dashboard', 'url' => route('dashboard'), 'active' => request()->routeIs('dashboard')],
                    ['label' => 'Progress', 'url' => route('user.progress'), 'active' => request()->routeIs('user.progress')],
                    ['label' => 'Edukasi', 'url' => route('contents.index'), 'active' => request()->routeIs('contents.*')],
                    ['label' => 'Buku', 'url' => route('books.index'), 'active' => request()->routeIs('books.index') || request()->routeIs('books.show')],
                    ['label' => 'Konsultasi', 'url' => route('consultations.index'), 'active' => request()->routeIs('consultations.*')],
                    ['label' => 'Forum', 'url' => route('forums.index'), 'active' => request()->routeIs('forums.*')],
                    
                ],
            };

            $navGroups = match (auth()->user()->role) {
                'admin' => [
                    ['title' => 'Platform', 'items' => array_slice($navItems, 0, 5)],
                    ['title' => 'Transaksi', 'items' => array_slice($navItems, 5)],
                ],
                'professional' => [
                    ['title' => 'Profesional', 'items' => $navItems],
                ],
                default => [
                    ['title' => 'Program', 'items' => $navItems],
                    ['title' => 'Akun', 'items' => [
                        ['label' => 'Buku Saya', 'url' => route('books.purchased'), 'active' => request()->routeIs('books.purchased') || request()->routeIs('books.read')],
                        ['label' => 'Pembayaran', 'url' => route('payments.index'), 'active' => request()->routeIs('payments.*')],
                        ['label' => 'Notifikasi', 'url' => route('notifications.index'), 'active' => request()->routeIs('notifications.*')],
                        ['label' => 'Profil Saya', 'url' => route('profile.edit'), 'active' => request()->routeIs('profile.*')],
                    ]],
                ],
            };

            $activeItem = collect($navGroups)->flatMap(fn($group) => $group['items'])->firstWhere('active') ?? $navItems[0];
            $navIcons = [
                'Dashboard' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 5a1 1 0 0 1 1-1h5v6H4V5Zm10-1h5a1 1 0 0 1 1 1v3h-6V4ZM4 14h6v6H5a1 1 0 0 1-1-1v-5Zm10-2h6v7a1 1 0 0 1-1 1h-5v-8Z"/></svg>',
                'Users' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 19a4 4 0 0 0-8 0M12 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm6.5 8a3.5 3.5 0 0 0-2.8-3.43M17 5.2a3 3 0 0 1 0 5.6"/></svg>',
                'Profesional' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 14.5a5 5 0 1 0 0-10 5 5 0 0 0 0 10Zm-7 6a7 7 0 0 1 14 0M12 7v5m-2.5-2.5h5"/></svg>',
                'Konten' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 4h9l3 3v13H6V4Zm8 0v4h4M9 12h6M9 16h6M9 8h2"/></svg>',
                'Forum' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 6h14v9H8l-3 3V6Zm4 4h6m-6 3h4"/></svg>',
                'Pembayaran' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 7h16v10H4V7Zm0 3h16M7 15h4"/></svg>',
                'E-Book' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 4h10a2 2 0 0 1 2 2v14H8a2 2 0 0 1-2-2V4Zm3 0v14a2 2 0 0 0 2 2m-1-12h5m-5 4h5"/></svg>',
                'Appointment' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 4v3m10-3v3M5 8h14v12H5V8Zm3 4h3v3H8v-3Z"/></svg>',
                'Janji Temu' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 4v3m10-3v3M5 8h14v12H5V8Zm3 4h3v3H8v-3Z"/></svg>',
                'Jadwal' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 5h14v14H5V5Zm0 4h14M9 3v4m6-4v4m-7 8h3m3 0h2"/></svg>',
                'Progress' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 19V5m0 14h14M9 16v-4m4 4V8m4 8v-6"/></svg>',
                'Edukasi' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 5.5A2.5 2.5 0 0 1 6.5 3H11v17H6.5A2.5 2.5 0 0 0 4 22V5.5Zm9-2.5h4.5A2.5 2.5 0 0 1 20 5.5V22a2.5 2.5 0 0 0-2.5-2H13V3Z"/></svg>',
                'Buku' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 4h10a2 2 0 0 1 2 2v14H8a2 2 0 0 1-2-2V4Zm3 0v14a2 2 0 0 0 2 2m-1-12h5m-5 4h5"/></svg>',
                'Buku Saya' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 4h10a2 2 0 0 1 2 2v14H8a2 2 0 0 1-2-2V4Zm4 4h5m-5 4h5m-5 4h3"/></svg>',
                'Konsultasi' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm-6 8a6 6 0 0 1 12 0M19 8h2m-1-1v2"/></svg>',
                'Notifikasi' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M18 16v-5a6 6 0 1 0-12 0v5l-2 2h16l-2-2Zm-8 4h4"/></svg>',
                'Profil Saya' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm-7 8a7 7 0 0 1 14 0"/></svg>',
                'Default' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 12h14M12 5v14"/></svg>',
            ];
        @endphp
    @else
        @php
            $homeRoute = url('/');
            $navItems = [];
        @endphp
    @endauth

    @auth
        @if(auth()->user()->role === 'admin')
        <div x-data="{ sidebarOpen: false, collapsed: false }">
            <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-[#1A1A1A]/40 backdrop-blur-sm lg:hidden"></div>

            <aside class="fixed inset-y-0 left-0 z-50 flex max-w-[92vw] -translate-x-full flex-row p-3 transition-transform duration-300 lg:translate-x-0"
                   :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">
                <div class="flex w-16 flex-col items-center gap-2 rounded-l-2xl border-r border-[#1A1A1A]/10 bg-white p-3 shadow-[12px_0_40px_rgba(0,0,0,0.08)]">
                    <a href="{{ $homeRoute }}" class="mb-2 flex h-10 w-10 items-center justify-center rounded-xl bg-[#F9F8F6] text-[#D4AF37] ring-1 ring-[#1A1A1A]/10" title="BerhentiMerokok">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </a>

                    <button type="button" @click="collapsed = !collapsed" class="mb-3 flex h-10 w-10 items-center justify-center rounded-lg text-[#6C6863] transition-colors duration-300 hover:bg-[#F9F8F6] hover:text-[#1A1A1A]" :title="collapsed ? 'Buka sidebar' : 'Tutup sidebar'" aria-label="Toggle sidebar">
                        <svg class="w-4 h-4 transition-transform duration-300" :class="collapsed ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m15 18-6-6 6-6"/>
                        </svg>
                    </button>

                    @foreach($navItems as $item)
                        <a href="{{ $item['url'] }}" title="{{ $item['label'] }}" class="flex h-10 w-10 items-center justify-center rounded-lg text-xs font-semibold uppercase transition-colors duration-300 {{ $item['active'] ? 'bg-[#D4AF37] text-[#1A1A1A]' : 'text-[#6C6863] hover:bg-[#F9F8F6] hover:text-[#1A1A1A]' }}">
                            {!! $navIcons[$item['label']] ?? $navIcons['Default'] !!}
                        </a>
                    @endforeach

                    <div class="flex-1"></div>
                    <a href="{{ route('home') }}" class="flex h-10 w-10 items-center justify-center rounded-lg text-[#6C6863] transition-colors duration-300 hover:bg-[#F9F8F6] hover:text-[#1A1A1A]" title="Beranda">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 11.5 12 4l9 7.5V20a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1v-8.5Z"/>
                        </svg>
                    </a>
                    <div class="flex h-9 w-9 items-center justify-center rounded-full border border-[#1A1A1A]/10 bg-[#EBE5DE] text-xs font-semibold text-[#1A1A1A]" title="{{ auth()->user()->name }}">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                </div>

                <div class="flex h-[calc(100vh-1.5rem)] flex-col overflow-hidden rounded-r-2xl border border-l-0 border-[#1A1A1A]/10 bg-white text-[#1A1A1A] shadow-[12px_0_40px_rgba(0,0,0,0.08)] transition-all duration-500"
                     :class="collapsed ? 'w-0 border-0 opacity-0 shadow-none' : 'w-72 sm:w-80 opacity-100'">
                    <div class="flex h-16 items-center justify-between border-b border-[#1A1A1A]/10 px-4">
                        <div x-show="!collapsed" x-transition.opacity class="min-w-0">
                            <p class="truncate font-serif text-lg text-[#1A1A1A]">BerhentiMerokok</p>
                            <p class="mt-0.5 text-[10px] uppercase tracking-[0.2em] text-[#6C6863]">{{ ucfirst(auth()->user()->role) }}</p>
                        </div>
                        <button type="button" @click="collapsed = true" class="flex h-10 w-10 items-center justify-center rounded-lg text-[#6C6863] transition-colors duration-300 hover:bg-[#F9F8F6] hover:text-[#1A1A1A]" aria-label="Collapse sidebar">
                            <svg class="w-4 h-4 transition-transform duration-300" :class="collapsed ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m15 18-6-6 6-6"/>
                            </svg>
                        </button>
                    </div>

                    <div class="border-b border-[#1A1A1A]/10 p-4" x-show="!collapsed" x-transition.opacity>
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full border border-[#1A1A1A]/10 bg-[#EBE5DE] text-sm font-semibold text-[#1A1A1A]">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                            <div class="min-w-0">
                                <p class="truncate text-sm font-medium text-[#1A1A1A]">{{ auth()->user()->name }}</p>
                                <p class="truncate text-xs text-[#6C6863]">{{ auth()->user()->email }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="border-b border-[#1A1A1A]/10 p-3">
                        <a href="{{ $activeItem['url'] }}" class="flex h-10 items-center rounded-lg border border-[#1A1A1A]/10 bg-[#F9F8F6] px-3 text-sm text-[#6C6863] transition-colors duration-300 hover:bg-[#EBE5DE]">
                            <svg class="h-4 w-4 shrink-0 text-[#6C6863]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m21 21-4.35-4.35M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Z"/>
                            </svg>
                            <span x-show="!collapsed" x-transition.opacity class="ml-3 truncate">Menu aktif: {{ $activeItem['label'] }}</span>
                        </a>
                    </div>

                    <nav class="flex-1 p-3 space-y-5 overflow-y-auto">
                        @foreach($navGroups as $group)
                            <div>
                                <p x-show="!collapsed" x-transition.opacity class="mb-2 px-3 text-xs text-[#6C6863]">{{ $group['title'] }}</p>
                                <div class="space-y-1">
                                    @foreach($group['items'] as $item)
                                        <a href="{{ $item['url'] }}" title="{{ $item['label'] }}" class="flex h-10 items-center rounded-lg px-3 text-sm transition-colors duration-300 {{ $item['active'] ? 'bg-[#D4AF37]/15 text-[#1A1A1A] ring-1 ring-[#D4AF37]/50' : 'text-[#6C6863] hover:bg-[#F9F8F6] hover:text-[#1A1A1A]' }}">
                                            <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded {{ $item['active'] ? 'bg-[#D4AF37] text-[#1A1A1A]' : 'bg-[#F9F8F6] text-[#6C6863] ring-1 ring-[#1A1A1A]/10' }}">{!! $navIcons[$item['label']] ?? $navIcons['Default'] !!}</span>
                                            <span x-show="!collapsed" x-transition.opacity class="ml-3 truncate">{{ $item['label'] }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </nav>

                    <div class="border-t border-[#1A1A1A]/10 p-3">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="flex items-center w-full h-10 px-3 text-sm text-red-700 transition-colors duration-300 rounded-lg hover:bg-red-700/5">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6A2.25 2.25 0 0 0 5.25 5.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12"/>
                                </svg>
                                <span x-show="!collapsed" x-transition.opacity class="ml-3">Keluar</span>
                            </button>
                        </form>
                    </div>
                </div>
            </aside>

            <div class="transition-[padding] duration-500" :class="collapsed ? 'lg:pl-[5.5rem]' : 'lg:pl-[26rem]'">
                <header class="sticky top-0 z-30 border-b border-[#1A1A1A]/10 bg-[#F9F8F6]/95 px-5 py-4 backdrop-blur sm:px-8 lg:hidden">
                    <div class="flex items-center justify-between gap-4">
                        <button type="button" @click="sidebarOpen = true" class="border border-[#1A1A1A]/20 px-3 py-2 text-[11px] font-medium uppercase tracking-[0.16em] text-[#1A1A1A]">Menu</button>
                        <span class="truncate font-serif text-lg text-[#1A1A1A]">BerhentiMerokok</span>
                    </div>
                </header>

                @if(session('success'))
                    <div class="mx-auto max-w-[1600px] px-5 pt-5 sm:px-8 md:px-12">
                        <div class="flex items-start gap-3 border border-[#D4AF37] bg-[#D4AF37]/10 px-4 py-3 text-sm leading-relaxed text-[#1A1A1A]">
                            <svg class="mt-0.5 h-5 w-5 shrink-0 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 13l4 4L19 7"/>
                            </svg>
                            {{ session('success') }}
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mx-auto max-w-[1600px] px-5 pt-5 sm:px-8 md:px-12">
                        <div class="flex items-start gap-3 px-4 py-3 text-sm leading-relaxed text-red-800 border border-red-700/40 bg-red-700/5">
                            <svg class="mt-0.5 h-5 w-5 shrink-0 text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            {{ session('error') }}
                        </div>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mx-auto max-w-[1600px] px-5 pt-5 sm:px-8 md:px-12">
                        <div class="border border-red-700/40 bg-red-700/5 px-4 py-3 text-sm leading-relaxed text-red-800">
                            <div class="flex items-start gap-3">
                                <svg class="mt-0.5 h-5 w-5 shrink-0 text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                                </svg>
                                <div>
                                    <p class="font-medium">Periksa kembali isian berikut:</p>
                                    <ul class="mt-1 list-inside list-disc space-y-0.5">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <main class="mx-auto max-w-[1600px] px-5 py-8 sm:px-8 sm:py-10 md:px-12 md:py-14">
                    @yield('content')
                </main>

                <footer class="mt-10 border-t border-[#1A1A1A]/10 bg-[#1A1A1A] md:mt-16">
                    <div class="mx-auto max-w-[1600px] px-5 py-8 text-center text-[10px] uppercase tracking-[0.2em] text-[#F9F8F6]/70 sm:px-8 md:px-12">
                        &copy; {{ date('Y') }} Platform Edukasi Berhenti Merokok - Universitas Paramadina
                    </div>
                </footer>
            </div>
        </div>
        @else
            <nav class="sticky top-0 z-50 border-b border-[#1A1A1A]/10 bg-[#F9F8F6]/95 backdrop-blur" x-data="{ mobileOpen: false, userOpen: false }">
                <div class="mx-auto max-w-[1600px] px-5 sm:px-8 md:px-16">
                    <div class="flex items-center justify-between gap-4 min-h-16 md:min-h-20">
                        <a href="{{ $homeRoute }}" class="flex items-center min-w-0 gap-3">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center border border-[#1A1A1A]">
                                <svg class="h-5 w-5 text-[#1A1A1A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                            </div>
                            <span class="truncate font-serif text-lg text-[#1A1A1A] sm:text-xl">BerhentiMerokok</span>
                        </a>

                        <div class="items-center hidden gap-6 lg:flex">
                            @foreach($navItems as $item)
                                <a href="{{ $item['url'] }}" class="editorial-link {{ $item['active'] ? 'text-[#D4AF37]' : '' }}">{{ $item['label'] }}</a>
                            @endforeach
                        </div>

                        <div class="flex items-center gap-2">
                            @if(auth()->user()->role === 'user')
                                <a href="{{ route('notifications.index') }}" class="hidden border border-transparent p-2 text-[#6C6863] transition-colors duration-500 hover:border-[#1A1A1A]/20 hover:text-[#D4AF37] sm:block">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                    <span class="hidden truncate max-w-32 md:block">{{ auth()->user()->name }}</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <div x-show="userOpen" @click.away="userOpen = false" x-cloak class="absolute right-0 z-50 mt-2 w-56 border border-[#1A1A1A]/10 bg-[#F9F8F6] py-2 shadow-[0_8px_32px_rgba(0,0,0,0.12)]">
                                    <a href="{{ route('home') }}" class="block px-4 py-3 text-xs font-medium uppercase tracking-[0.2em] text-[#1A1A1A] transition-colors duration-500 hover:text-[#D4AF37]">Beranda</a>
                                    @if(auth()->user()->role === 'user')
                                        <a href="{{ route('books.purchased') }}" class="block px-4 py-3 text-xs font-medium uppercase tracking-[0.2em] text-[#1A1A1A] transition-colors duration-500 hover:text-[#D4AF37]">Buku Saya</a>
                                        <a href="{{ route('consultations.appointments') }}" class="block px-4 py-3 text-xs font-medium uppercase tracking-[0.2em] text-[#1A1A1A] transition-colors duration-500 hover:text-[#D4AF37]">Janji Temu</a>
                                        <a href="{{ route('payments.index') }}" class="block px-4 py-3 text-xs font-medium uppercase tracking-[0.2em] text-[#1A1A1A] transition-colors duration-500 hover:text-[#D4AF37]">Pembayaran</a>
                                    @endif
                                    @if(auth()->user()->role === 'professional')
                                        <a href="{{ route('professional.schedule') }}" class="block px-4 py-3 text-xs font-medium uppercase tracking-[0.2em] text-[#1A1A1A] transition-colors duration-500 hover:text-[#D4AF37]">Jadwal</a>
                                    @else
                                        <a href="{{ route('profile.edit') }}" class="block px-4 py-3 text-xs font-medium uppercase tracking-[0.2em] text-[#1A1A1A] transition-colors duration-500 hover:text-[#D4AF37]">Profil Saya</a>
                                    @endif
                                    <hr class="my-1 border-[#1A1A1A]/10">
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full px-4 py-3 text-left text-xs font-medium uppercase tracking-[0.2em] text-red-700 hover:bg-red-700/5">Keluar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div x-show="mobileOpen" x-cloak class="border-t border-[#1A1A1A]/10 py-4 lg:hidden">
                        <div class="grid gap-2">
                            @foreach($navItems as $item)
                                <a href="{{ $item['url'] }}" class="border border-[#1A1A1A]/10 px-4 py-3 text-xs font-medium uppercase tracking-[0.18em] text-[#1A1A1A] {{ $item['active'] ? 'border-[#D4AF37] text-[#D4AF37]' : '' }}">{{ $item['label'] }}</a>
                            @endforeach
                            <a href="{{ route('home') }}" class="border border-[#1A1A1A]/10 px-4 py-3 text-xs font-medium uppercase tracking-[0.18em] text-[#1A1A1A]">Beranda</a>
                            @if(auth()->user()->role === 'user')
                                <a href="{{ route('notifications.index') }}" class="border border-[#1A1A1A]/10 px-4 py-3 text-xs font-medium uppercase tracking-[0.18em] text-[#1A1A1A]">Notifikasi</a>
                                <a href="{{ route('books.purchased') }}" class="border border-[#1A1A1A]/10 px-4 py-3 text-xs font-medium uppercase tracking-[0.18em] text-[#1A1A1A]">Buku Saya</a>
                                <a href="{{ route('consultations.appointments') }}" class="border border-[#1A1A1A]/10 px-4 py-3 text-xs font-medium uppercase tracking-[0.18em] text-[#1A1A1A]">Janji Temu</a>
                                <a href="{{ route('payments.index') }}" class="border border-[#1A1A1A]/10 px-4 py-3 text-xs font-medium uppercase tracking-[0.18em] text-[#1A1A1A]">Pembayaran</a>
                                <a href="{{ route('profile.edit') }}" class="border border-[#1A1A1A]/10 px-4 py-3 text-xs font-medium uppercase tracking-[0.18em] text-[#1A1A1A]">Profil Saya</a>
                            @endif
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full border border-red-700/40 px-4 py-3 text-left text-xs font-medium uppercase tracking-[0.18em] text-red-700">Keluar</button>
                            </form>
                        </div>
                    </div>
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
                    <div class="flex items-start gap-3 px-4 py-3 text-sm leading-relaxed text-red-800 border border-red-700/40 bg-red-700/5">
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
        @endif
    @else
        <nav class="sticky top-0 z-50 border-b border-[#1A1A1A]/10 bg-[#F9F8F6]/95 backdrop-blur">
            <div class="mx-auto flex min-h-16 max-w-[1600px] items-center justify-between gap-4 px-5 sm:px-8 md:min-h-20 md:px-16">
                <a href="{{ $homeRoute }}" class="flex items-center min-w-0 gap-3">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center border border-[#1A1A1A]">
                        <svg class="h-5 w-5 text-[#1A1A1A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </div>
                    <span class="truncate font-serif text-lg text-[#1A1A1A] sm:text-xl">BerhentiMerokok</span>
                </a>
                <div class="flex items-center gap-2">
                    <a href="{{ route('login') }}" class="btn-secondary">Masuk</a>
                    <a href="{{ route('register') }}" class="btn-primary"><span>Daftar</span></a>
                </div>
            </div>
        </nav>

        <main class="mx-auto max-w-[1600px] px-5 py-8 sm:px-8 sm:py-10 md:px-16 md:py-16">
            @yield('content')
        </main>
    @endauth

    @stack('scripts')
    <script src="//unpkg.com/alpinejs" defer></script>
</body>
</html>
