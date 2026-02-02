<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Services\Admin\AdminDashboardService;

class AdminController extends Controller
{
    public function dashboard(AdminDashboardService $dashboardService)
    {
        $data = $dashboardService->getDashboardData();

        return view('admin.dashboard', $data);
    }

    public function config()
    {
        $countries = Country::orderBy('name')->get();

        return view('admin.config.index', compact('countries'));
    }
}
