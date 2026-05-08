<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Content;
use App\Models\Book;
use App\Models\Forum;
use App\Models\Professional;

class HomeController extends Controller
{
    public function index()
    {
        $stats = [
            'contents'      => Content::where('approval_status', 'approved')->count(),
            'books'         => Book::count(),
            'professionals' => Professional::where('is_verified', true)->count(),
            'forums'        => Forum::count(),
        ];

        $tracker = null;
        if (Auth::user()->role === 'user') {
            $tracker = DB::table('progress_trackers')
                ->where('user_id', Auth::id())
                ->first();
        }

        $latestContents = Content::where('approval_status', 'approved')
            ->latest()
            ->limit(3)
            ->get();

        return view('home', compact('stats', 'tracker', 'latestContents'));
    }
}
