@extends('layouts.app')

@section('title', 'Tulis Konten')

@section('content')
    <div class="mx-auto max-w-2xl">
        <div class="mb-10 border-b border-[#1A1A1A]/10 pb-8">
            <span class="editorial-label">Kontribusi</span>
            <h1 class="mt-2 page-title">Tulis Konten Edukasi</h1>
        </div>

        <div class="mb-6 border border-[#D4AF37] bg-[#D4AF37]/10 px-4 py-3 text-sm text-[#1A1A1A]">
            Konten yang kamu kirim akan ditinjau oleh admin sebelum dipublikasikan.
        </div>

        <div class="card">
            <form action="{{ route('contents.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <div>
                    <label class="editorial-label mb-2 block">Judul</label>
                    <input type="text" name="title" value="{{ old('title') }}" required class="input-field" placeholder="Judul konten">
                    @error('title')<p class="mt-1 text-xs text-red-700">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="editorial-label mb-2 block">Jenis Konten</label>
                    <select name="type" class="input-field">
                        <option value="artikel" {{ old('type') === 'artikel' ? 'selected' : '' }}>Artikel</option>
                        <option value="video" {{ old('type') === 'video' ? 'selected' : '' }}>Video</option>
                        <option value="infografis" {{ old('type') === 'infografis' ? 'selected' : '' }}>Infografis</option>
                    </select>
                </div>
                <div>
                    <label class="editorial-label mb-2 block">Ringkasan <span class="text-[#6C6863] normal-case tracking-normal">(opsional)</span></label>
                    <textarea name="description" rows="2" class="input-field resize-none" placeholder="Ringkasan singkat konten (tampil di daftar)">{{ old('description') }}</textarea>
                </div>
                <div>
                    <label class="editorial-label mb-2 block">Gambar / Infografis <span class="text-[#6C6863] normal-case tracking-normal">(opsional, maks 2 MB)</span></label>
                    <input type="file" name="image" accept="image/jpeg,image/png,image/webp" class="input-field file:mr-4 file:border-0 file:bg-[#1A1A1A] file:px-4 file:py-2 file:text-xs file:uppercase file:tracking-wider file:text-[#EBE5DE]">
                    @error('image')<p class="mt-1 text-xs text-red-700">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="editorial-label mb-2 block">Upload Video <span class="text-[#6C6863] normal-case tracking-normal">(opsional, MP4/WebM/MOV, maks 50 MB)</span></label>
                    <input type="file" name="video" accept="video/mp4,video/webm,video/quicktime" class="input-field file:mr-4 file:border-0 file:bg-[#1A1A1A] file:px-4 file:py-2 file:text-xs file:uppercase file:tracking-wider file:text-[#EBE5DE]">
                    @error('video')<p class="mt-1 text-xs text-red-700">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="editorial-label mb-2 block">…atau Link Video <span class="text-[#6C6863] normal-case tracking-normal">(opsional, YouTube)</span></label>
                    <input type="url" name="video_url" value="{{ old('video_url') }}" class="input-field" placeholder="https://www.youtube.com/watch?v=...">
                    @error('video_url')<p class="mt-1 text-xs text-red-700">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="editorial-label mb-2 block">Isi Konten</label>
                    <textarea name="body" rows="12" required class="input-field resize-none" placeholder="Tulis isi kontenmu di sini...">{{ old('body') }}</textarea>
                    @error('body')<p class="mt-1 text-xs text-red-700">{{ $message }}</p>@enderror
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="btn-primary"><span>Kirim untuk Review</span></button>
                    <a href="{{ auth()->user()->role === 'admin' ? route('admin.contents') : route('contents.index') }}" class="btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
