<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TestBooking;
use App\Models\TestResultParameter;
use Illuminate\Http\Request;

class ResultEntryController extends Controller
{
    public function edit(TestBooking $booking)
    {
        $booking->load(['items.result.parameters', 'items.labTest', 'patient']);

        return view('admin.results.edit', compact('booking'));
    }

    public function update(Request $request, TestBooking $booking)
    {
        $request->validate([
            'results' => 'required|array',
            'results.*' => 'nullable|string',
        ]);

        foreach ($request->results as $parameterId => $value) {
            TestResultParameter::where('id', $parameterId)->update([
                'result_value' => $value,
            ]);
        }

        // Update statuses of the results
        foreach ($booking->items as $item) {
            if ($item->result) {
                $item->result->update(['status' => 'verified']);
            }
        }

        $booking->update(['status' => 'completed']);

        return redirect()->route('admin.bookings.index')->with('success', 'Test results saved successfully.');
    }
}
