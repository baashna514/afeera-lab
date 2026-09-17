<?php

namespace App\Http\Controllers;

use App\Models\TestBooking;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FetchPaymentsController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        // Fetch test bookings with their associated test items/lab tests
        $payments = TestBooking::with(['items.labTest'])
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $payments,
        ]);
    }
}
