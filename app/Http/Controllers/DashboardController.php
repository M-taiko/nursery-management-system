<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(private DashboardService $dashboardService) {}

    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->hasRole('Admin')) {
            $data = $this->dashboardService->getAdminDashboard();
            return view('dashboard.admin', compact('data'));
        }

        if ($user->hasRole('Teacher')) {
            $data = $this->dashboardService->getTeacherDashboard($user->id);
            return view('dashboard.teacher', compact('data'));
        }

        if ($user->hasRole('Parent')) {
            $data = $this->dashboardService->getParentDashboard($user->id);
            return view('dashboard.parent', compact('data'));
        }

        return redirect()->route('login');
    }
}
