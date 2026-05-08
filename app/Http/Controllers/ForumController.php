<?php

namespace App\Http\Controllers;

use App\Models\Forum;
use App\Models\ForumReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForumController extends Controller
{
    public function index()
    {
        $forums = Forum::withCount('forumReplies')
            ->with('user')
            ->latest()
            ->paginate(15);

        return view('forums.index', compact('forums'));
    }

    public function show(Forum $forum)
    {
        $forum->increment('views');
        $replies = $forum->forumReplies()->with('user')->latest()->get();
        return view('forums.show', compact('forum', 'replies'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body'  => ['required', 'string'],
        ]);

        Forum::create([
            'user_id' => Auth::id(),
            'title'   => $data['title'],
            'body'    => $data['body'],
        ]);

        return redirect()->route('forums.index')->with('success', 'Thread berhasil dibuat!');
    }

    public function reply(Request $request, Forum $forum)
    {
        $data = $request->validate(['body' => ['required', 'string']]);

        ForumReply::create([
            'forum_id' => $forum->id,
            'user_id'  => Auth::id(),
            'body'     => $data['body'],
        ]);

        return redirect()->route('forums.show', $forum)->with('success', 'Balasan berhasil dikirim!');
    }
}
