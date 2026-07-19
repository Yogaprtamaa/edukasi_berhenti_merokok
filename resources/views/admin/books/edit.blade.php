@extends('layouts.app')

@section('title', 'Edit Buku')

@section('content')
    <div class="mx-auto max-w-2xl">
        <div class="mb-10 border-b border-[#1A1A1A]/10 pb-8">
            <a href="{{ route('admin.books') }}" class="editorial-label text-[#6C6863] hover:text-[#1A1A1A]">← Kembali</a>
            <h1 class="mt-4 page-title">Edit <span class="italic text-[#D4AF37]">Buku</span></h1>
        </div>

        <div class="card">
            <form action="{{ route('admin.books.update', $book) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')
                @include('admin.books._form')
                <div class="flex gap-3 pt-2">
                    <button type="submit" class="btn-primary"><span>Simpan Perubahan</span></button>
                    <a href="{{ route('admin.books') }}" class="btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
