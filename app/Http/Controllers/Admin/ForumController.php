<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Forum;
use App\Models\ForumReply;
use Illuminate\Http\Request;

class ForumController extends Controller
{
    public function index()
    {
        $forums = Forum::with('user')
            ->latest()
            ->paginate(20);

        // MongoDB tidak mendukung withCount lintas-koleksi; hitung via relasi.
        $forums->getCollection()->each(function ($forum) {
            $forum->forum_replies_count = $forum->forumReplies()->count();
        });

        return view('admin.forums.index', compact('forums'));
    }

    public function edit(Forum $forum)
    {
        $forum->load(['user', 'forumReplies.user']);
        return view('admin.forums.edit', compact('forum'));
    }

    public function update(Request $request, Forum $forum)
    {
        $data = $request->validate([
            'title'   => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
        ]);

        $forum->update([
            'title' => $data['title'],
            'body' => $data['content'],
            'content' => $data['content'],
        ]);

        return redirect()->route('admin.forums')->with('success', 'Thread forum berhasil diperbarui.');
    }

    public function destroy(Forum $forum)
    {
        $forum->forumReplies()->delete();
        $forum->delete();

        return back()->with('success', 'Thread forum berhasil dihapus.');
    }

    public function destroyReply(ForumReply $reply)
    {
        $reply->delete();
        return back()->with('success', 'Balasan berhasil dihapus.');
    }
}
