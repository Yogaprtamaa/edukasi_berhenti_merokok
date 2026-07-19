@extends('layouts.app')

@section('title', 'Moderasi Konten')

@section('content')
    <header class="mb-10 md:mb-12">
        <div class="mb-4 flex items-center gap-3 sm:mb-5 sm:gap-4">
            <span class="h-px w-8 bg-[#1A1A1A] sm:w-12"></span>
            <span class="editorial-label">Kurasi Edukasi</span>
        </div>
        <h1 class="page-title">Moderasi <span class="italic text-[#D4AF37]">Konten</span></h1>
        <p class="mt-4 max-w-xl text-sm leading-relaxed text-[#6C6863]">Review dan publikasikan materi edukasi yang dikirim pengguna atau profesional.</p>
        <a href="{{ route('contents.create') }}" class="btn-primary mt-6 inline-flex"><span>Tambah Konten</span></a>
    </header>

    <div class="card">
        @forelse($contents as $content)
            <div class="grid gap-4 border-b border-[#1A1A1A]/10 py-5 last:border-0 lg:grid-cols-[1fr_auto]">
                <div class="min-w-0">
                    <div class="mb-3 flex flex-wrap items-center gap-2">
                        @if($content->approval_status === 'pending')
                            <span class="badge-yellow">Pending</span>
                        @elseif($content->approval_status === 'approved')
                            <span class="badge-green">Disetujui</span>
                        @else
                            <span class="badge-red">Ditolak</span>
                        @endif
                        <span class="editorial-label">{{ ucfirst($content->type) }}</span>
                    </div>
                    <p class="truncate font-medium text-[#1A1A1A]">{{ $content->title }}</p>
                    <p class="mt-1 text-sm text-[#6C6863]">oleh {{ $content->uploader?->name ?? 'Admin' }} - {{ \Carbon\Carbon::parse($content->created_at)->format('d M Y') }}</p>
                    <p class="mt-2 text-sm leading-relaxed text-[#6C6863]">{{ Str::limit(strip_tags($content->body), 150) }}</p>
                </div>
                <div class="flex flex-wrap items-center gap-2 lg:justify-end">
                    @if($content->approval_status === 'pending')
                        <form action="{{ route('admin.contents.approve', $content) }}" method="POST" class="flex">
                            @csrf
                            <button class="badge-green cursor-pointer">Setujui</button>
                        </form>
                        <form action="{{ route('admin.contents.reject', $content) }}" method="POST" class="flex">
                            @csrf
                            <button class="badge-red cursor-pointer">Tolak</button>
                        </form>
                    @endif
                    <a href="{{ route('admin.contents.edit', $content) }}" class="badge-yellow cursor-pointer">Edit</a>
                    <form action="{{ route('admin.contents.destroy', $content) }}" method="POST" class="flex" onsubmit="return confirm('Hapus konten ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="badge-red cursor-pointer">Hapus</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="py-12 text-center text-sm text-[#6C6863]">Tidak ada konten.</div>
        @endforelse
    </div>

    <div class="mt-6">{{ $contents->links() }}</div>
@endsection
