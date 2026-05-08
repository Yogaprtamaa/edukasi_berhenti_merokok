@extends('layouts.app')

@section('title', 'Konten Edukasi')

@section('content')
    <div class="mb-10 flex items-end justify-between border-b border-[#1A1A1A]/10 pb-8">
        <div>
            <span class="editorial-label">Perpustakaan</span>
            <h1 class="mt-2 page-title">Konten Edukasi</h1>
            <p class="mt-2 text-sm text-[#6C6863]">Artikel, video, dan infografis seputar berhenti merokok</p>
        </div>
        <a href="{{ route('contents.create') }}" class="btn-primary shrink-0"><span>Tulis Artikel</span></a>
    </div>

    <div class="grid gap-px bg-[#1A1A1A]/10 md:grid-cols-2 lg:grid-cols-3">
        @forelse($contents as $content)
            <a href="{{ route('contents.show', $content) }}" class="group flex flex-col bg-[#F9F8F6] p-6 transition-colors duration-700 hover:bg-[#EBE5DE] md:p-8">
                <div class="mb-4">
                    @if($content->type === 'video')
                        <span class="badge-red">Video</span>
                    @elseif($content->type === 'infografis')
                        <span class="badge-blue">Infografis</span>
                    @else
                        <span class="badge-green">Artikel</span>
                    @endif
                </div>
                <h3 class="font-serif text-lg leading-snug text-[#1A1A1A] line-clamp-2 transition-colors duration-500 group-hover:text-[#D4AF37]">{{ $content->title }}</h3>
                <p class="mt-2 flex-1 text-sm text-[#6C6863] line-clamp-2">{{ Str::limit(strip_tags($content->body), 100) }}</p>
                <div class="mt-6 flex items-center gap-2 border-t border-[#1A1A1A]/8 pt-4">
                    <span class="editorial-label">{{ $content->uploader?->name ?? 'Admin' }}</span>
                    <span class="text-[#1A1A1A]/20">·</span>
                    <span class="editorial-label">{{ \Carbon\Carbon::parse($content->published_at)->diffForHumans() }}</span>
                </div>
            </a>
        @empty
            <div class="col-span-3 py-20 text-center text-[#6C6863]">
                <p class="font-serif text-xl">Belum ada konten tersedia</p>
            </div>
        @endforelse
    </div>

    <div class="mt-8">{{ $contents->links() }}</div>
@endsection
