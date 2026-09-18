<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LabTest;
use App\Models\TestBooking;
use App\Models\TestBookingItem;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{
    public function earnings(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : null;
        $endDate = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : null;
        $selectedTestId = $request->test_id;

        $query = TestBooking::query();

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        if ($selectedTestId) {
            $query->whereHas('items', function ($q) use ($selectedTestId) {
                $q->where('lab_test_id', $selectedTestId);
            });
        }

        // Daily aggregates for the table
        $earningsByDate = $query->selectRaw('DATE(created_at) as date, COUNT(*) as total_bookings, SUM(total_amount) as total_invoiced, SUM(paid_amount) as total_paid')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->paginate(15)
            ->withQueryString();

        $earningsByDate->getCollection()->transform(function ($row) use ($selectedTestId) {
            $q = TestBookingItem::whereHas('booking', function ($q) use ($row) {
                $q->whereDate('created_at', $row->date);
            });
            if ($selectedTestId) {
                $q->where('lab_test_id', $selectedTestId);
            }
            $row->total_tests = $q->count();

            return $row;
        });

        // Summary Statistics (Respecting test_id filter if applied)
        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek();
        $startOfMonth = Carbon::now()->startOfMonth();

        $statsQuery = function ($dateConstraint = null) use ($selectedTestId) {
            $q = TestBooking::query();
            if ($selectedTestId) {
                $q->whereHas('items', function ($itemQ) use ($selectedTestId) {
                    $itemQ->where('lab_test_id', $selectedTestId);
                });
            }
            if ($dateConstraint) {
                $dateConstraint($q);
            }

            return (float) $q->sum('paid_amount');
        };

        $todayTotal = $statsQuery(fn ($q) => $q->whereDate('created_at', $today));
        $weeklyTotal = $statsQuery(fn ($q) => $q->where('created_at', '>=', $startOfWeek));
        $monthlyTotal = $statsQuery(fn ($q) => $q->where('created_at', '>=', $startOfMonth));
        $overallTotal = $statsQuery();

        // Revenue Breakdown By Test (Specific Test Performance Report)
        $testBreakdownQuery = TestBookingItem::with('labTest')
            ->selectRaw('lab_test_id, COUNT(*) as test_count, SUM(price) as total_revenue')
            ->groupBy('lab_test_id');

        if ($startDate && $endDate) {
            $testBreakdownQuery->whereBetween('created_at', [$startDate, $endDate]);
        }

        if ($selectedTestId) {
            $testBreakdownQuery->where('lab_test_id', $selectedTestId);
        }

        $testBreakdown = $testBreakdownQuery->get();

        // All tests for the dropdown filter
        $availableTests = LabTest::orderBy('name')->get();

        return view('admin.reports.earnings', compact(
            'earningsByDate',
            'todayTotal',
            'weeklyTotal',
            'monthlyTotal',
            'overallTotal',
            'startDate',
            'endDate',
            'testBreakdown',
            'availableTests',
            'selectedTestId'
        ));
    }
}
