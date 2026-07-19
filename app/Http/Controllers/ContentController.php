<?php

namespace App\Http\Controllers;

use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ContentController extends Controller
{
    public function index()
    {
        $contents = Content::where('is_published', true)
            ->with('uploader')
            ->latest('published_at')
            ->paginate(12);

        return view('contents.index', compact('contents'));
    }

    public function show(Content $content)
    {
        abort_if(!$content->is_published, 404);
        return view('contents.show', compact('content'));
    }

    public function create()
    {
        return view('contents.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string', 'max:500'],
            'body'        => ['required', 'string'],
            'type'        => ['required', 'in:artikel,video,infografis'],
            'image'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'video'       => ['nullable', 'file', 'mimetypes:video/mp4,video/webm,video/quicktime', 'max:51200'],
            'video_url'   => ['nullable', 'url', 'max:500'],
        ]);

        $thumbnailUrl = $request->hasFile('image')
            ? Storage::url($request->file('image')->store('contents', 'public'))
            : null;

        // File upload menang atas link kalau dua-duanya diisi.
        $videoUrl = $request->hasFile('video')
            ? Storage::url($request->file('video')->store('contents/videos', 'public'))
            : ($data['video_url'] ?? null);

        // Admin adalah pihak yang meng-approve, jadi kontennya langsung terbit.
        $isAdmin = Auth::user()->role === 'admin';

        Content::create([
            'uploader_id'     => Auth::id(),
            'uploader_role'   => Auth::user()->role,
            'title'           => $data['title'],
            'description'     => $data['description'] ?? null,
            'body'            => $data['body'],
            'type'            => $data['type'],
            'thumbnail_url'   => $thumbnailUrl,
            'video_url'       => $videoUrl,
            'approval_status' => $isAdmin ? 'approved' : 'pending',
            'is_published'    => $isAdmin,
            'published_at'    => $isAdmin ? now() : null,
        ]);

        return $isAdmin
            ? redirect()->route('admin.contents')->with('success', 'Konten berhasil ditambahkan dan langsung terbit.')
            : redirect()->route('contents.index')->with('success', 'Konten berhasil dikirim dan menunggu persetujuan admin.');
    }
}
