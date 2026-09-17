<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TestBookingItem;
use Illuminate\Http\Request;

class SampleCollectionController extends Controller
{
    public function index(Request $request)
    {
        $query = TestBookingItem::with(['booking.patient', 'labTest'])->latest();

        if ($request->filled('status')) {
            $query->where('sample_status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('barcode', 'like', "%{$search}%")
                    ->orWhereHas('booking', function ($b) use ($search) {
                        $b->where('invoice_number', 'like', "%{$search}%")
                            ->orWhereHas('patient', function ($p) use ($search) {
                                $p->where('name', 'like', "%{$search}%")
                                    ->orWhere('phone', 'like', "%{$search}%");
                            });
                    });
            });
        }

        $samples = $query->paginate(15)->withQueryString();

        $pendingCount = TestBookingItem::where('sample_status', 'pending')->count();
        $collectedCount = TestBookingItem::where('sample_status', 'collected')->count();

        return view('admin.samples.index', compact('samples', 'pendingCount', 'collectedCount'));
    }

    public function collect(TestBookingItem $item)
    {
        $item->update([
            'sample_status' => 'collected',
            'collected_at' => now(),
        ]);

        return back()->with('success', "Sample {$item->barcode} for {$item->labTest->name} marked as collected successfully!");
    }

    public function printBarcode(TestBookingItem $item)
    {
        $item->load(['booking.patient', 'labTest']);

        return view('admin.samples.barcode', compact('item'));
    }
}
