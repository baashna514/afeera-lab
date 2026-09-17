<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TestBooking;
use Illuminate\Http\Request;

class PathologistReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = TestBooking::with(['patient', 'items.labTest', 'items.result'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                    ->orWhereHas('patient', function ($p) use ($search) {
                        $p->where('name', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    });
            });
        }

        $bookings = $query->paginate(15)->withQueryString();

        $pendingReviewsCount = TestBooking::where('status', 'completed')
            ->whereHas('items.result', function ($q) {
                $q->where('status', '!=', 'verified');
            })->count();

        $verifiedCount = TestBooking::whereHas('items.result', function ($q) {
            $q->where('status', 'verified');
        })->count();

        return view('admin.reviews.index', compact('bookings', 'pendingReviewsCount', 'verifiedCount'));
    }

    public function show(TestBooking $booking)
    {
        $booking->load(['patient', 'items.labTest.notes', 'items.result.parameters', 'items.result.verifiedBy']);

        return view('admin.reviews.show', compact('booking'));
    }

    public function approve(Request $request, TestBooking $booking)
    {
        $booking->load('items.result');

        foreach ($booking->items as $item) {
            if ($item->result) {
                $item->result->update([
                    'status' => 'verified',
                    'verified_by' => auth()->id(),
                    'verified_at' => now(),
                    'remarks' => $request->remarks ?? 'Clinically correlated and verified by Pathologist.',
                ]);
            }
        }

        $booking->update(['status' => 'completed']);

        return redirect()->route('admin.reviews.show', $booking)->with('success', 'Medical report reviewed & verified by Pathologist successfully!');
    }

    public function printReport(TestBooking $booking)
    {
        $booking->load(['patient', 'items.labTest.notes', 'items.result.parameters', 'items.result.verifiedBy']);
        $company = auth()->user()->company;

        return view('admin.reviews.report', compact('booking', 'company'));
    }
}
