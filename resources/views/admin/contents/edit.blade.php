@extends('layouts.app')

@section('title', 'Edit Konten')

@section('content')
    <div class="mx-auto max-w-2xl">
        <div class="mb-10 border-b border-[#1A1A1A]/10 pb-8">
            <a href="{{ route('admin.contents') }}" class="editorial-label text-[#6C6863] hover:text-[#1A1A1A]">← Kembali</a>
            <h1 class="mt-4 page-title">Edit <span class="italic text-[#D4AF37]">Konten</span></h1>
            <p class="mt-2 text-sm text-[#6C6863]">oleh {{ $content->uploader?->name ?? 'Admin' }}</p>
        </div>

        <div class="card">
            <form action="{{ route('admin.contents.update', $content) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label class="editorial-label mb-2 block">Judul</label>
                    <input type="text" name="title" value="{{ old('title', $content->title) }}" required class="input-field">
                    @error('title')<p class="mt-1 text-xs text-red-700">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="editorial-label mb-2 block">Jenis Konten</label>
                    <select name="type" class="input-field">
                        <option value="artikel" {{ old('type', $content->type) === 'artikel' ? 'selected' : '' }}>Artikel</option>
                        <option value="video" {{ old('type', $content->type) === 'video' ? 'selected' : '' }}>Video</option>
                        <option value="infografis" {{ old('type', $content->type) === 'infografis' ? 'selected' : '' }}>Infografis</option>
                    </select>
                </div>

                <div>
                    <label class="editorial-label mb-2 block">Status</label>
                    <select name="approval_status" class="input-field">
                        <option value="pending" {{ old('approval_status', $content->approval_status) === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ old('approval_status', $content->approval_status) === 'approved' ? 'selected' : '' }}>Disetujui</option>
                        <option value="rejected" {{ old('approval_status', $content->approval_status) === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>

                <div>
                    <label class="editorial-label mb-2 block">Ringkasan <span class="normal-case tracking-normal text-[#6C6863]">(opsional)</span></label>
                    <textarea name="description" rows="2" class="input-field resize-none">{{ old('description', $content->description) }}</textarea>
                </div>

                <div>
                    <label class="editorial-label mb-2 block">Isi Konten</label>
                    <textarea name="body" rows="14" required class="input-field resize-none">{{ old('body', $content->body) }}</textarea>
                    @error('body')<p class="mt-1 text-xs text-red-700">{{ $message }}</p>@enderror
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit" class="btn-primary"><span>Simpan Perubahan</span></button>
                    <a href="{{ route('admin.contents') }}" class="btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
