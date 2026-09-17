<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TestBooking;
use App\Models\TestBookingItem;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    /**
     * Display the company super admin dashboard.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $company = $user->company;

        $todayPatients = TestBooking::whereDate('created_at', today())->count();
        $pendingSamples = TestBookingItem::where('sample_status', 'pending')->count();
        $pendingResults = TestBooking::where('status', 'pending')->count();
        $pendingReview = TestBooking::where('status', 'completed')
            ->whereHas('items.result', function ($q) {
                $q->where('status', '!=', 'verified');
            })->count();

        return view('admin.dashboard', compact(
            'user',
            'company',
            'todayPatients',
            'pendingSamples',
            'pendingResults',
            'pendingReview'
        ));
    }
}
