@extends('layouts.app')

@section('title', 'Notifikasi')

@section('content')
    <div class="mb-10 border-b border-[#1A1A1A]/10 pb-8">
        <span class="editorial-label">Inbox</span>
        <h1 class="mt-2 page-title">Notifikasi</h1>
    </div>

    <div class="mx-auto max-w-2xl">
        <div class="card">
            @forelse($notifications as $notif)
                <div class="flex items-start gap-4 border-b border-[#1A1A1A]/8 py-4 last:border-0 {{ !$notif->is_read ? 'bg-[#EBE5DE]/40 -mx-4 px-4 md:-mx-8 md:px-8' : '' }}">
                    <div class="mt-0.5 flex h-9 w-9 flex-shrink-0 items-center justify-center border border-[#1A1A1A]/20 {{ !$notif->is_read ? 'bg-[#D4AF37]/20 border-[#D4AF37]' : 'bg-[#EBE5DE]' }}">
                        <svg class="h-4 w-4 {{ !$notif->is_read ? 'text-[#D4AF37]' : 'text-[#6C6863]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-[#1A1A1A]">{{ $notif->title }}</p>
                        <p class="mt-0.5 text-sm text-[#6C6863]">{{ $notif->message }}</p>
                        <p class="editorial-label mt-2">{{ \Carbon\Carbon::parse($notif->sent_at)->diffForHumans() }}</p>
                    </div>
                    @if(!$notif->is_read)
                        <div class="mt-2 h-2 w-2 flex-shrink-0 bg-[#D4AF37]"></div>
                    @endif
                </div>
            @empty
                <div class="py-16 text-center text-[#6C6863]">
                    <p class="font-serif text-xl">Belum ada notifikasi</p>
                </div>
            @endforelse
        </div>

        <div class="mt-6">{{ $notifications->links() }}</div>
    </div>
@endsection
