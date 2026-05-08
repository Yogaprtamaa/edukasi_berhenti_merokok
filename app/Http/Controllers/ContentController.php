<?php

namespace App\Http\Controllers;

use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        ]);

        Content::create([
            'uploader_id'     => Auth::id(),
            'uploader_role'   => Auth::user()->role,
            'title'           => $data['title'],
            'description'     => $data['description'] ?? null,
            'body'            => $data['body'],
            'type'            => $data['type'],
            'approval_status' => 'pending',
            'is_published'    => false,
        ]);

        return redirect()->route('contents.index')
            ->with('success', 'Konten berhasil dikirim dan menunggu persetujuan admin.');
    }
}
