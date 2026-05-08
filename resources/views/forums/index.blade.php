@extends('layouts.app')

@section('title', 'Forum Komunitas')

@section('content')
    <div class="mb-10 flex items-end justify-between border-b border-[#1A1A1A]/10 pb-8">
        <div>
            <span class="editorial-label">Komunitas</span>
            <h1 class="mt-2 page-title">Forum Diskusi</h1>
            <p class="mt-2 text-sm text-[#6C6863]">Diskusi dan saling dukung sesama pejuang</p>
        </div>
        <button onclick="document.getElementById('modal-forum').classList.remove('hidden')" class="btn-primary shrink-0">
            <span>Buat Thread</span>
        </button>
    </div>

    <div class="space-y-0">
        @forelse($forums as $forum)
            <a href="{{ route('forums.show', $forum) }}" class="group flex items-start gap-4 border-b border-[#1A1A1A]/8 py-5 last:border-0 transition-colors duration-300 hover:bg-[#EBE5DE]/30 -mx-4 px-4 md:-mx-8 md:px-8">
                <div class="mt-0.5 flex h-9 w-9 flex-shrink-0 items-center justify-center border border-[#1A1A1A]/20 bg-[#EBE5DE]">
                    <span class="text-xs font-medium text-[#1A1A1A]">{{ strtoupper(substr($forum->user->name, 0, 1)) }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="font-serif text-lg text-[#1A1A1A] line-clamp-1 transition-colors duration-500 group-hover:text-[#D4AF37]">{{ $forum->title }}</h3>
                    <p class="mt-1 text-sm text-[#6C6863] line-clamp-1">{{ Str::limit($forum->body, 120) }}</p>
                    <div class="mt-2 flex items-center gap-4">
                        <span class="editorial-label">{{ $forum->user->name }}</span>
                        <span class="editorial-label">{{ $forum->forum_replies_count }} balasan</span>
                        <span class="editorial-label">{{ $forum->views }} dilihat</span>
                        <span class="editorial-label">{{ \Carbon\Carbon::parse($forum->created_at)->diffForHumans() }}</span>
                    </div>
                </div>
            </a>
        @empty
            <div class="py-20 text-center text-[#6C6863]">
                <p class="font-serif text-xl">Belum ada diskusi. Jadilah yang pertama!</p>
            </div>
        @endforelse
    </div>

    <div class="mt-8">{{ $forums->links() }}</div>

    {{-- Modal Buat Thread --}}
    <div id="modal-forum" class="fixed inset-0 z-50 hidden items-center justify-center bg-[#1A1A1A]/60 p-4 flex">
        <div class="w-full max-w-lg bg-[#F9F8F6] border border-[#1A1A1A] p-8">
            <div class="mb-6 flex items-center justify-between border-b border-[#1A1A1A]/10 pb-6">
                <h2 class="font-serif text-xl text-[#1A1A1A]">Buat Thread Baru</h2>
                <button onclick="document.getElementById('modal-forum').classList.add('hidden')" class="text-[#6C6863] transition-colors duration-300 hover:text-[#1A1A1A]">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form action="{{ route('forums.store') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="editorial-label mb-2 block">Judul Thread</label>
                    <input type="text" name="title" required class="input-field" placeholder="Judul diskusi">
                </div>
                <div>
                    <label class="editorial-label mb-2 block">Isi</label>
                    <textarea name="body" rows="5" required class="input-field resize-none" placeholder="Ceritakan pengalamanmu..."></textarea>
                </div>
                <button type="submit" class="btn-primary w-full"><span>Posting Thread</span></button>
            </form>
        </div>
    </div>
@endsection
