<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TestBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{
    public function earnings(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : null;
        $endDate = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : null;

        $query = TestBooking::query();

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        // Daily aggregates for the table
        $earningsByDate = $query->selectRaw('DATE(created_at) as date, COUNT(*) as total_bookings, SUM(total_amount) as total_invoiced, SUM(paid_amount) as total_paid')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->paginate(15);

        // Summary Statistics
        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek();
        $startOfMonth = Carbon::now()->startOfMonth();

        $todayTotal = TestBooking::whereDate('created_at', $today)->sum('paid_amount');
        $weeklyTotal = TestBooking::where('created_at', '>=', $startOfWeek)->sum('paid_amount');
        $monthlyTotal = TestBooking::where('created_at', '>=', $startOfMonth)->sum('paid_amount');
        $overallTotal = TestBooking::sum('paid_amount');

        return view('admin.reports.earnings', compact(
            'earningsByDate',
            'todayTotal',
            'weeklyTotal',
            'monthlyTotal',
            'overallTotal',
            'startDate',
            'endDate'
        ));
    }
}
