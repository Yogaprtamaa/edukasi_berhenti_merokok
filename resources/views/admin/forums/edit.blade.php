@extends('layouts.app')

@section('title', 'Edit Thread Forum')

@section('content')
    <div class="mx-auto max-w-2xl">
        <div class="mb-10 border-b border-[#1A1A1A]/10 pb-8">
            <a href="{{ route('admin.forums') }}" class="editorial-label text-[#6C6863] hover:text-[#1A1A1A]">← Kembali</a>
            <h1 class="mt-4 page-title">Edit <span class="italic text-[#D4AF37]">Thread</span></h1>
            <p class="mt-2 text-sm text-[#6C6863]">oleh {{ $forum->user?->name ?? '—' }}</p>
        </div>

        <div class="card">
            <form action="{{ route('admin.forums.update', $forum) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label class="editorial-label mb-2 block">Judul</label>
                    <input type="text" name="title" value="{{ old('title', $forum->title) }}" required class="input-field">
                    @error('title')<p class="mt-1 text-xs text-red-700">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="editorial-label mb-2 block">Isi</label>
                    <textarea name="content" rows="10" required class="input-field resize-none">{{ old('content', $forum->content) }}</textarea>
                    @error('content')<p class="mt-1 text-xs text-red-700">{{ $message }}</p>@enderror
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit" class="btn-primary"><span>Simpan Perubahan</span></button>
                    <a href="{{ route('admin.forums') }}" class="btn-secondary">Batal</a>
                </div>
            </form>
        </div>

        {{-- Balasan --}}
        @if($forum->forumReplies->count())
            <div class="mt-10">
                <span class="editorial-label text-[#6C6863]">Balasan ({{ $forum->forumReplies->count() }})</span>
                <div class="mt-4 card">
                    @foreach($forum->forumReplies as $reply)
                        <div class="grid gap-3 border-b border-[#1A1A1A]/10 py-4 last:border-0 sm:grid-cols-[1fr_auto] sm:items-start">
                            <div>
                                <p class="text-xs font-medium text-[#D4AF37]">{{ $reply->user?->name ?? '—' }} · {{ \Carbon\Carbon::parse($reply->created_at)->diffForHumans() }}</p>
                                <p class="mt-2 text-sm leading-relaxed text-[#1A1A1A]">{{ $reply->content }}</p>
                            </div>
                            <form action="{{ route('admin.forum-replies.destroy', $reply) }}" method="POST" onsubmit="return confirm('Hapus balasan ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="badge-red cursor-pointer">Hapus</button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection
