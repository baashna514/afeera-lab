<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\View\View;

class OwnerDashboardController extends Controller
{
    /**
     * Display the owner dashboard.
     */
    public function index(): View
    {
        $totalCompanies = Company::count();
        $activeCompanies = Company::where('status', 'active')->count();
        $inactiveCompanies = Company::where('status', 'inactive')->count();
        $totalSuperAdmins = User::where('role', 'super_admin')->count();

        $recentCompanies = Company::with('superAdmin')
            ->latest()
            ->take(5)
            ->get();

        return view('owner.dashboard', compact(
            'totalCompanies',
            'activeCompanies',
            'inactiveCompanies',
            'totalSuperAdmins',
            'recentCompanies'
        ));
    }
}
