<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Content;
use App\Models\Professional;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers        = User::where('role', 'user')->count();
        $newUsersThisMonth = User::where('role', 'user')
            ->whereMonth('created_at', now()->month)
            ->count();

        $totalProfessionals   = Professional::where('is_verified', true)->count();
        $pendingProfessionals = Professional::where('is_verified', false)->count();

        $pendingContents = Content::where('approval_status', 'pending')->count();

        $totalAppointments = Appointment::count();

        $pendingProfessionalList = Professional::with('user')
            ->where('is_verified', false)
            ->latest()
            ->take(5)
            ->get();

        $pendingContentList = Content::with('uploader')
            ->where('approval_status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'newUsersThisMonth',
            'totalProfessionals',
            'pendingProfessionals',
            'pendingContents',
            'totalAppointments',
            'pendingProfessionalList',
            'pendingContentList'
        ));
    }
}
