@extends('layouts.app')

@section('title', 'Admin Dashboard - BerhentiMerokok')

@section('content')
    <header class="mb-8 overflow-hidden border border-[#1A1A1A]/10 bg-white shadow-[0_16px_50px_rgba(26,26,26,0.06)]">
        <div class="grid gap-6 p-5 sm:p-7 lg:grid-cols-[1fr_auto] lg:items-end">
            <div>
                <div class="mb-4 flex items-center gap-3">
                    <span class="flex h-9 w-9 items-center justify-center bg-[#1A1A1A] text-[#F9F8F6]">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 5a1 1 0 0 1 1-1h5v6H4V5Zm10-1h5a1 1 0 0 1 1 1v3h-6V4ZM4 14h6v6H5a1 1 0 0 1-1-1v-5Zm10-2h6v7a1 1 0 0 1-1 1h-5v-8Z"/>
                        </svg>
                    </span>
                    <span class="editorial-label">Admin Console</span>
                </div>
                <h1 class="font-serif text-4xl leading-none text-[#1A1A1A] sm:text-5xl">
                    Ringkasan Platform
                </h1>
                <p class="mt-3 max-w-2xl text-sm leading-relaxed text-[#6C6863] sm:text-base">
                    Pantau kesehatan operasional, transaksi, appointment, dan antrean moderasi dari satu layar kerja.
                </p>
            </div>
            <div class="grid gap-2 sm:grid-cols-3 lg:min-w-[460px]">
                <a href="{{ route('admin.payments') }}" class="border border-[#1A1A1A]/10 bg-[#F9F8F6] px-4 py-3 transition-colors duration-300 hover:border-[#D4AF37]">
                    <p class="text-[10px] uppercase tracking-[0.18em] text-[#6C6863]">Pending Pay</p>
                    <p class="mt-1 font-serif text-2xl text-[#1A1A1A]">{{ $pendingPayments }}</p>
                </a>
                <a href="{{ route('admin.professionals') }}" class="border border-[#1A1A1A]/10 bg-[#F9F8F6] px-4 py-3 transition-colors duration-300 hover:border-[#D4AF37]">
                    <p class="text-[10px] uppercase tracking-[0.18em] text-[#6C6863]">Verifikasi</p>
                    <p class="mt-1 font-serif text-2xl text-[#1A1A1A]">{{ $pendingProfessionals }}</p>
                </a>
                <a href="{{ route('admin.contents') }}" class="border border-[#1A1A1A]/10 bg-[#F9F8F6] px-4 py-3 transition-colors duration-300 hover:border-[#D4AF37]">
                    <p class="text-[10px] uppercase tracking-[0.18em] text-[#6C6863]">Konten</p>
                    <p class="mt-1 font-serif text-2xl text-[#1A1A1A]">{{ $pendingContents }}</p>
                </a>
            </div>
        </div>
    </header>

    <section class="mb-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="border border-[#1A1A1A]/10 bg-white p-5 shadow-[0_10px_30px_rgba(26,26,26,0.04)]">
            <div class="flex items-start justify-between gap-4">
                <p class="editorial-label">Pengguna</p>
                <span class="flex h-9 w-9 items-center justify-center bg-[#EBE5DE] text-[#1A1A1A]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 19a4 4 0 0 0-8 0M12 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z"/></svg>
                </span>
            </div>
            <p class="mt-5 font-serif text-4xl leading-none text-[#1A1A1A]">{{ $totalUsers }}</p>
            <p class="mt-3 text-sm text-[#6C6863]">+{{ $newUsersThisMonth }} user baru bulan ini</p>
        </div>
        <div class="border border-[#1A1A1A]/10 bg-white p-5 shadow-[0_10px_30px_rgba(26,26,26,0.04)]">
            <div class="flex items-start justify-between gap-4">
                <p class="editorial-label">Profesional</p>
                <span class="flex h-9 w-9 items-center justify-center bg-[#D4AF37]/20 text-[#1A1A1A]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 14.5a5 5 0 1 0 0-10 5 5 0 0 0 0 10Zm-7 6a7 7 0 0 1 14 0M12 7v5m-2.5-2.5h5"/></svg>
                </span>
            </div>
            <p class="mt-5 font-serif text-4xl leading-none text-[#1A1A1A]">{{ $totalProfessionals }}</p>
            <p class="mt-3 text-sm text-[#6C6863]">{{ $pendingProfessionals }} menunggu review</p>
        </div>
        <div class="border border-[#1A1A1A]/10 bg-white p-5 shadow-[0_10px_30px_rgba(26,26,26,0.04)]">
            <div class="flex items-start justify-between gap-4">
                <p class="editorial-label">Konsultasi</p>
                <span class="flex h-9 w-9 items-center justify-center bg-[#EBE5DE] text-[#1A1A1A]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 4v3m10-3v3M5 8h14v12H5V8Zm3 4h3v3H8v-3Z"/></svg>
                </span>
            </div>
            <p class="mt-5 font-serif text-4xl leading-none text-[#1A1A1A]">{{ $totalAppointments }}</p>
            <p class="mt-3 text-sm text-[#6C6863]">{{ $pendingAppointments }} pending</p>
        </div>
        <div class="border border-[#D4AF37]/60 bg-[#D4AF37]/10 p-5 shadow-[0_10px_30px_rgba(212,175,55,0.10)]">
            <div class="flex items-start justify-between gap-4">
                <p class="editorial-label">Revenue</p>
                <span class="flex h-9 w-9 items-center justify-center bg-white text-[#1A1A1A]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 7h16v10H4V7Zm0 3h16M7 15h4"/></svg>
                </span>
            </div>
            <p class="mt-5 font-serif text-3xl leading-none text-[#1A1A1A]">Rp{{ number_format($totalRevenue, 0, ',', '.') }}</p>
            <p class="mt-3 text-sm text-[#6C6863]">{{ $totalOrders }} order e-book</p>
        </div>
    </section>

    <section class="mb-10 grid gap-5 xl:grid-cols-12 md:mb-14">
        <div class="border border-[#1A1A1A]/10 bg-white p-5 shadow-[0_16px_50px_rgba(26,26,26,0.05)] sm:p-6 xl:col-span-7">
            <div class="mb-6 flex flex-wrap items-start justify-between gap-4">
                <div>
                    <p class="editorial-label">Revenue</p>
                    <h2 class="mt-2 text-2xl text-[#1A1A1A] sm:text-3xl">Tren 6 Bulan</h2>
                </div>
                <p class="text-right font-serif text-2xl text-[#1A1A1A]">Rp{{ number_format($totalRevenue, 0, ',', '.') }}</p>
            </div>

            <div class="relative grid h-72 grid-cols-6 items-end gap-3 border-b border-[#1A1A1A]/10 pb-4 sm:gap-5">
                <div class="pointer-events-none absolute inset-x-0 top-0 h-px bg-[#1A1A1A]/5"></div>
                <div class="pointer-events-none absolute inset-x-0 top-1/3 h-px bg-[#1A1A1A]/5"></div>
                <div class="pointer-events-none absolute inset-x-0 top-2/3 h-px bg-[#1A1A1A]/5"></div>
                @foreach($revenueChart as $point)
                    @php
                        $height = max(($point['amount'] / $maxRevenue) * 100, $point['amount'] > 0 ? 8 : 2);
                    @endphp
                    <div class="flex h-full min-w-0 flex-col justify-end gap-3">
                        <div class="flex flex-1 items-end">
                            <div class="relative w-full overflow-hidden border border-[#1A1A1A]/10 bg-[#F9F8F6]">
                                <div class="absolute bottom-0 w-full bg-[#D4AF37] transition-all duration-700" style="height: {{ $height }}%"></div>
                            </div>
                        </div>
                        <div class="text-center">
                            <p class="truncate text-xs font-medium text-[#1A1A1A]">{{ $point['label'] }}</p>
                            <p class="mt-1 truncate text-[11px] text-[#6C6863]">Rp{{ number_format($point['amount'], 0, ',', '.') }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="border border-[#1A1A1A]/10 bg-white p-5 shadow-[0_16px_50px_rgba(26,26,26,0.05)] sm:p-6 xl:col-span-5">
            <div class="mb-6">
                <p class="editorial-label">Distribusi</p>
                <h2 class="mt-2 text-2xl text-[#1A1A1A] sm:text-3xl">Tipe Transaksi</h2>
            </div>

            @php
                $ebookPercent = round(($transactionTypeChart['ebook'] / $transactionTypeTotal) * 100);
                $consultationPercent = 100 - $ebookPercent;
            @endphp
            <div class="grid gap-6 sm:grid-cols-[180px_1fr] sm:items-center">
                <div class="relative mx-auto flex h-44 w-44 items-center justify-center rounded-full border border-[#1A1A1A]/10"
                     style="background: conic-gradient(#D4AF37 0 {{ $ebookPercent }}%, #1A1A1A {{ $ebookPercent }}% 100%);">
                    <div class="flex h-28 w-28 flex-col items-center justify-center rounded-full bg-white text-center shadow-inner">
                        <span class="editorial-label">E-Book</span>
                        <span class="font-serif text-3xl text-[#1A1A1A]">{{ $ebookPercent }}%</span>
                    </div>
                </div>
                <div class="space-y-5">
                    <div>
                        <div class="mb-2 flex justify-between text-sm">
                            <span class="font-medium text-[#1A1A1A]">E-Book</span>
                            <span class="text-[#6C6863]">{{ $transactionTypeChart['ebook'] }} transaksi</span>
                        </div>
                        <div class="h-2 bg-[#EBE5DE]"><div class="h-2 bg-[#D4AF37]" style="width: {{ $ebookPercent }}%"></div></div>
                    </div>
                    <div>
                        <div class="mb-2 flex justify-between text-sm">
                            <span class="font-medium text-[#1A1A1A]">Konsultasi</span>
                            <span class="text-[#6C6863]">{{ $transactionTypeChart['consultation'] }} transaksi</span>
                        </div>
                        <div class="h-2 bg-[#EBE5DE]"><div class="h-2 bg-[#1A1A1A]" style="width: {{ $consultationPercent }}%"></div></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="border border-[#1A1A1A]/10 bg-white p-5 shadow-[0_16px_50px_rgba(26,26,26,0.05)] sm:p-6 xl:col-span-6">
            <div class="mb-6">
                <p class="editorial-label">Pembayaran</p>
                <h2 class="mt-2 text-2xl text-[#1A1A1A] sm:text-3xl">Status Pembayaran</h2>
            </div>
            <div class="space-y-4">
                @foreach($paymentStatusChart as $status)
                    @php
                        $maxPaymentStatus = max($paymentStatusChart->max('count'), 1);
                        $width = ($status['count'] / $maxPaymentStatus) * 100;
                    @endphp
                    <div>
                        <div class="mb-2 flex justify-between text-sm">
                            <span class="font-medium text-[#1A1A1A]">{{ $status['label'] }}</span>
                            <span class="text-[#6C6863]">{{ $status['count'] }}</span>
                        </div>
                        <div class="h-3 bg-[#EBE5DE]">
                            <div class="h-3 {{ $status['label'] === 'Success' ? 'bg-[#D4AF37]' : ($status['label'] === 'Pending' ? 'bg-[#1A1A1A]' : 'bg-red-700/60') }}" style="width: {{ $width }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="border border-[#1A1A1A]/10 bg-white p-5 shadow-[0_16px_50px_rgba(26,26,26,0.05)] sm:p-6 xl:col-span-6">
            <div class="mb-6">
                <p class="editorial-label">Konsultasi</p>
                <h2 class="mt-2 text-2xl text-[#1A1A1A] sm:text-3xl">Status Appointment</h2>
            </div>
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                @foreach($appointmentStatusChart as $status)
                    @php
                        $maxAppointmentStatus = max($appointmentStatusChart->max('count'), 1);
                        $height = max(($status['count'] / $maxAppointmentStatus) * 100, $status['count'] > 0 ? 12 : 4);
                    @endphp
                    <div class="border border-[#1A1A1A]/10 p-3">
                        <div class="mb-3 flex h-24 items-end bg-[#EBE5DE]">
                            <div class="w-full bg-[#D4AF37]" style="height: {{ $height }}%"></div>
                        </div>
                        <p class="truncate text-sm font-medium text-[#1A1A1A]">{{ $status['label'] }}</p>
                        <p class="mt-1 text-2xl font-serif text-[#1A1A1A]">{{ $status['count'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="grid gap-5 lg:grid-cols-3">
        <div class="border border-[#1A1A1A]/10 bg-white p-5 shadow-[0_16px_50px_rgba(26,26,26,0.05)] sm:p-6">
            <div class="mb-5 grid gap-3 sm:mb-6 sm:flex sm:items-start sm:justify-between">
                <div>
                    <p class="editorial-label">Verifikasi</p>
                    <h2 class="mt-2 text-2xl text-[#1A1A1A] sm:text-3xl">Profesional Pending</h2>
                </div>
                <a href="{{ route('admin.professionals') }}" class="editorial-link">Lihat Semua</a>
            </div>
            @forelse($pendingProfessionalList as $prof)
                <div class="grid gap-3 border-t border-[#1A1A1A]/10 py-4 first:border-t-0 sm:grid-cols-[1fr_auto] sm:items-center">
                    <div class="flex min-w-0 items-center gap-3">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center bg-[#EBE5DE] text-xs font-semibold text-[#1A1A1A]">{{ strtoupper(substr($prof->user->name, 0, 1)) }}</span>
                        <div class="min-w-0">
                            <p class="truncate font-medium text-[#1A1A1A]">{{ $prof->user->name }}</p>
                            <p class="mt-1 truncate text-sm text-[#6C6863]">{{ ucfirst($prof->type) }} - {{ $prof->specialization }}</p>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2 sm:justify-end">
                        <form action="{{ route('admin.professionals.approve', $prof) }}" method="POST">
                            @csrf
                            <button class="badge-green cursor-pointer transition-colors duration-500 hover:border-[#D4AF37]">Setujui</button>
                        </form>
                        <a href="{{ route('admin.professionals.show', $prof) }}" class="badge-blue">Detail</a>
                    </div>
                </div>
            @empty
                <p class="py-10 text-center text-sm text-[#6C6863]">Tidak ada pengajuan pending.</p>
            @endforelse
        </div>

        <div class="border border-[#1A1A1A]/10 bg-white p-5 shadow-[0_16px_50px_rgba(26,26,26,0.05)] sm:p-6">
            <div class="mb-5 grid gap-3 sm:mb-6 sm:flex sm:items-start sm:justify-between">
                <div>
                    <p class="editorial-label">Moderasi</p>
                    <h2 class="mt-2 text-2xl text-[#1A1A1A] sm:text-3xl">Konten Menunggu</h2>
                </div>
                <a href="{{ route('admin.contents') }}" class="editorial-link">Lihat Semua</a>
            </div>
            @forelse($pendingContentList as $content)
                <div class="grid gap-3 border-t border-[#1A1A1A]/10 py-4 first:border-t-0 sm:grid-cols-[1fr_auto] sm:items-center">
                    <div class="min-w-0">
                        <p class="truncate font-medium text-[#1A1A1A]">{{ $content->title }}</p>
                        <p class="mt-1 text-sm text-[#6C6863]">oleh {{ $content->uploader?->name ?? 'Admin' }} - {{ ucfirst($content->type) }}</p>
                    </div>
                    <div class="flex flex-wrap gap-2 sm:justify-end">
                        <form action="{{ route('admin.contents.approve', $content) }}" method="POST">
                            @csrf
                            <button class="badge-green cursor-pointer transition-colors duration-500 hover:border-[#D4AF37]">Setujui</button>
                        </form>
                        <form action="{{ route('admin.contents.reject', $content) }}" method="POST">
                            @csrf
                            <button class="badge-red cursor-pointer transition-colors duration-500 hover:bg-red-700/10">Tolak</button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="py-10 text-center text-sm text-[#6C6863]">Tidak ada konten pending.</p>
            @endforelse
        </div>

        <div class="border border-[#1A1A1A]/10 bg-white p-5 shadow-[0_16px_50px_rgba(26,26,26,0.05)] sm:p-6">
            <div class="mb-5 grid gap-3 sm:mb-6 sm:flex sm:items-start sm:justify-between">
                <div>
                    <p class="editorial-label">Transaksi</p>
                    <h2 class="mt-2 text-2xl text-[#1A1A1A] sm:text-3xl">Pembayaran Terbaru</h2>
                </div>
                <a href="{{ route('admin.payments') }}" class="editorial-link">Lihat Semua</a>
            </div>
            @forelse($recentPayments as $payment)
                <div class="grid gap-3 border-t border-[#1A1A1A]/10 py-4 first:border-t-0 sm:grid-cols-[1fr_auto] sm:items-center">
                    <div class="min-w-0">
                        <div class="mb-2 flex flex-wrap items-center gap-2">
                            @if($payment->status === 'success')
                                <span class="badge-green">Sukses</span>
                            @elseif($payment->status === 'pending')
                                <span class="badge-yellow">Pending</span>
                            @else
                                <span class="badge-red">{{ ucfirst($payment->status) }}</span>
                            @endif
                            <span class="editorial-label">{{ $payment->order ? 'E-Book' : 'Konsultasi' }}</span>
                        </div>
                        <p class="truncate font-medium text-[#1A1A1A]">{{ $payment->user?->name ?? 'Pengguna dihapus' }}</p>
                        <p class="mt-1 text-sm text-[#6C6863]">Rp{{ number_format($payment->amount, 0, ',', '.') }}</p>
                    </div>
                    <a href="{{ route('admin.payments.show', $payment) }}" class="badge-blue">Detail</a>
                </div>
            @empty
                <p class="py-10 text-center text-sm text-[#6C6863]">Belum ada pembayaran.</p>
            @endforelse
        </div>
    </section>
@endsection
