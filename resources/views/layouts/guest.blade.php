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

    <div class="flex min-h-screen">
        {{-- Kolom Kiri — Editorial Panel (hanya desktop) --}}
        <div class="relative hidden lg:flex lg:w-1/2 xl:w-[55%] flex-col justify-between bg-[#1A1A1A] p-12 xl:p-16 overflow-hidden">
            {{-- Grid lines dekoratif --}}
            <div class="pointer-events-none absolute inset-0">
                <div class="absolute left-1/3 top-0 h-full w-px bg-[#F9F8F6]/5"></div>
                <div class="absolute left-2/3 top-0 h-full w-px bg-[#F9F8F6]/5"></div>
            </div>

            {{-- Logo atas --}}
            <div>
                <span class="editorial-label text-[#EBE5DE]/50">Platform Edukasi</span>
                <div class="mt-4 h-px w-12 bg-[#D4AF37]"></div>
            </div>

            {{-- Konten tengah --}}
            <div class="relative z-10">
                <p class="editorial-label text-[#D4AF37] mb-6">Universitas Paramadina</p>
                <h1 class="font-serif text-6xl leading-[0.9] text-[#F9F8F6] xl:text-7xl">
                    Berhenti<br>
                    <span class="italic text-[#D4AF37]">Merokok</span><br>
                    Mulai Hari Ini
                </h1>
                <p class="mt-8 max-w-sm text-sm leading-relaxed text-[#EBE5DE]/60">
                    Platform edukasi terpadu untuk mendukung perjalanan berhenti merokok. Konsultasi profesional, konten edukasi, dan komunitas yang saling mendukung.
                </p>

                <div class="mt-12 grid grid-cols-3 gap-6 border-t border-[#F9F8F6]/10 pt-10">
                    <div>
                        <p class="font-serif text-3xl text-[#F9F8F6]">95%</p>
                        <p class="editorial-label mt-1 text-[#EBE5DE]/40">Tingkat kepuasan</p>
                    </div>
                    <div>
                        <p class="font-serif text-3xl text-[#D4AF37]">500+</p>
                        <p class="editorial-label mt-1 text-[#EBE5DE]/40">Pengguna aktif</p>
                    </div>
                    <div>
                        <p class="font-serif text-3xl text-[#F9F8F6]">20+</p>
                        <p class="editorial-label mt-1 text-[#EBE5DE]/40">Profesional</p>
                    </div>
                </div>
            </div>

            {{-- Footer kiri --}}
            <div>
                <p class="text-[10px] tracking-[0.2em] uppercase text-[#EBE5DE]/30">© 2026 BerhentiMerokok</p>
            </div>
        </div>

        {{-- Kolom Kanan — Form --}}
        <div class="flex w-full flex-col justify-center px-8 py-12 lg:w-1/2 xl:w-[45%] lg:px-16 xl:px-20">
            {{-- Logo mobile (hanya muncul di mobile) --}}
            <div class="mb-10 lg:hidden">
                <span class="editorial-label">Platform Edukasi</span>
                <div class="mt-3 font-serif text-4xl leading-none text-[#1A1A1A]">
                    Berhenti<br><span class="italic text-[#D4AF37]">Merokok</span>
                </div>
                <div class="mt-5 h-px w-12 bg-[#1A1A1A]"></div>
                <p class="mt-3 text-sm text-[#6C6863]">Universitas Paramadina</p>
            </div>

            {{-- Form area --}}
            <div class="w-full max-w-sm lg:max-w-md">
                <div class="border-t border-[#1A1A1A] pt-8">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
</body>
</html>
