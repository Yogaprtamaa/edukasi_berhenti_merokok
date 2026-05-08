@extends('layouts.app')

@section('title', $content->title)

@section('content')
    <div class="mx-auto max-w-3xl">
        <a href="{{ route('contents.index') }}" class="inline-flex items-center gap-2 text-xs text-[#6C6863] transition-colors duration-500 hover:text-[#D4AF37] mb-8">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            <span class="editorial-label">Kembali ke Konten</span>
        </a>

        <div class="card">
            <div class="mb-4">
                @if($content->type === 'video')
                    <span class="badge-red">Video</span>
                @elseif($content->type === 'infografis')
                    <span class="badge-blue">Infografis</span>
                @else
                    <span class="badge-green">Artikel</span>
                @endif
            </div>

            <h1 class="font-serif text-3xl leading-tight text-[#1A1A1A] md:text-4xl">{{ $content->title }}</h1>

            <div class="mt-6 flex items-center gap-4 border-b border-[#1A1A1A]/10 pb-6">
                <div class="flex h-9 w-9 items-center justify-center border border-[#1A1A1A]/20 bg-[#EBE5DE]">
                    <span class="text-xs font-medium text-[#1A1A1A]">{{ strtoupper(substr($content->uploader?->name ?? 'A', 0, 1)) }}</span>
                </div>
                <div>
                    <p class="text-sm text-[#1A1A1A]">{{ $content->uploader?->name ?? 'Admin' }}</p>
                    <p class="editorial-label">{{ \Carbon\Carbon::parse($content->published_at)->format('d M Y') }}</p>
                </div>
            </div>

            <div class="mt-6 text-[#1A1A1A] leading-relaxed">
                {!! nl2br(e($content->body)) !!}
            </div>
        </div>
    </div>
@endsection
