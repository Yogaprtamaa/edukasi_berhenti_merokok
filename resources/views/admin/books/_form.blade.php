@php($book = $book ?? null)

<div>
    <label class="editorial-label mb-2 block">Judul</label>
    <input type="text" name="title" value="{{ old('title', $book?->title) }}" required class="input-field">
    @error('title')<p class="mt-1 text-xs text-red-700">{{ $message }}</p>@enderror
</div>

<div>
    <label class="editorial-label mb-2 block">Penulis</label>
    <input type="text" name="author" value="{{ old('author', $book?->author) }}" required class="input-field">
    @error('author')<p class="mt-1 text-xs text-red-700">{{ $message }}</p>@enderror
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="editorial-label mb-2 block">Harga (Rp)</label>
        <input type="number" name="price" min="0" step="0.01" value="{{ old('price', $book?->price) }}" required class="input-field">
        @error('price')<p class="mt-1 text-xs text-red-700">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="editorial-label mb-2 block">Stok</label>
        <input type="number" name="stock" min="0" value="{{ old('stock', $book?->stock ?? 0) }}" required class="input-field">
        @error('stock')<p class="mt-1 text-xs text-red-700">{{ $message }}</p>@enderror
    </div>
</div>

<div>
    <label class="editorial-label mb-2 block">ISBN <span class="normal-case tracking-normal text-[#6C6863]">(opsional)</span></label>
    <input type="text" name="isbn" value="{{ old('isbn', $book?->isbn) }}" class="input-field">
</div>

<div>
    <label class="editorial-label mb-2 block">Deskripsi</label>
    <textarea name="description" rows="6" required class="input-field resize-none">{{ old('description', $book?->description) }}</textarea>
    @error('description')<p class="mt-1 text-xs text-red-700">{{ $message }}</p>@enderror
</div>

<div>
    <label class="editorial-label mb-2 block">Cover <span class="normal-case tracking-normal text-[#6C6863]">(opsional, maks 2 MB)</span></label>
    @if($book?->cover_url)
        <img src="{{ $book->cover_url }}" alt="Cover saat ini" class="mb-3 h-32 w-24 object-cover border border-[#1A1A1A]/10">
    @endif
    <input type="file" name="cover" accept="image/jpeg,image/png,image/webp" class="input-field file:mr-4 file:border-0 file:bg-[#1A1A1A] file:px-4 file:py-2 file:text-xs file:uppercase file:tracking-wider file:text-[#EBE5DE]">
    @error('cover')<p class="mt-1 text-xs text-red-700">{{ $message }}</p>@enderror
</div>

<div class="flex items-center gap-3">
    <input type="checkbox" id="is_available" name="is_available" value="1" {{ old('is_available', $book?->is_available ?? true) ? 'checked' : '' }} class="h-4 w-4">
    <label for="is_available" class="text-sm text-[#1A1A1A]">Tersedia untuk dibeli</label>
</div>
