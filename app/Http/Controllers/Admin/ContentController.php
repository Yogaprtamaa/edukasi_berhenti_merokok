<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Models\ContentApproval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContentController extends Controller
{
    public function index()
    {
        $contents = Content::with('uploader')
            ->latest()
            ->paginate(20);

        return view('admin.contents.index', compact('contents'));
    }

    public function edit(Content $content)
    {
        return view('admin.contents.edit', compact('content'));
    }

    public function update(Request $request, Content $content)
    {
        $data = $request->validate([
            'title'           => ['required', 'string', 'max:200'],
            'description'     => ['nullable', 'string', 'max:500'],
            'body'            => ['required', 'string'],
            'type'            => ['required', 'in:artikel,video,infografis'],
            'approval_status' => ['required', 'in:pending,approved,rejected'],
        ]);

        if ($data['approval_status'] === 'approved' && $content->approval_status !== 'approved') {
            $data['is_published'] = true;
            $data['published_at'] = now();
        } elseif ($data['approval_status'] !== 'approved') {
            $data['is_published'] = false;
        }

        $content->update($data);

        return redirect()->route('admin.contents')->with('success', 'Konten berhasil diperbarui.');
    }

    public function destroy(Content $content)
    {
        $content->delete();
        return back()->with('success', 'Konten berhasil dihapus.');
    }

    public function approve(Content $content)
    {
        $content->update([
            'approval_status' => 'approved',
            'is_published'    => true,
            'published_at'    => now(),
        ]);

        ContentApproval::updateOrCreate(
            ['content_id' => $content->id],
            ['admin_id' => Auth::id(), 'status' => 'approved', 'processed_at' => now()]
        );

        return back()->with('success', 'Konten berhasil dipublikasikan.');
    }

    public function reject(Request $request, Content $content)
    {
        $request->validate(['notes' => ['nullable', 'string']]);

        $content->update(['approval_status' => 'rejected']);

        ContentApproval::updateOrCreate(
            ['content_id' => $content->id],
            [
                'admin_id'     => Auth::id(),
                'status'       => 'rejected',
                'notes'        => $request->notes,
                'processed_at' => now(),
            ]
        );

        return back()->with('success', 'Konten ditolak.');
    }
}
