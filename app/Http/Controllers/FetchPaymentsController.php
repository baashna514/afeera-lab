<?php

namespace App\Http\Controllers;

use App\Models\TestBooking;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FetchPaymentsController extends Controller
{
    /**
     * Handle the incoming request to fetch all payments and associated tests.
     */
    public function __invoke(Request $request): JsonResponse
    {
        // Fetch test bookings with patient, company, and test items
        $query = TestBooking::withoutGlobalScopes()
            ->with([
                'company:id,name,email,phone',
                'patient:id,name,phone,age,gender',
                'items.labTest:id,name,category,code,price',
            ])
            ->latest();

        // Optional date filter if provided in query params (e.g. ?date=2026-09-18 or ?from=...&to=...)
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('created_at', [$request->from, $request->to]);
        }

        $bookings = $query->get();

        // Transform into a clean, comprehensive, null-safe format
        $payments = $bookings->map(function ($booking) {
            $totalAmount = (float) ($booking->total_amount ?? 0);
            $discount = (float) ($booking->discount ?? 0);
            $paidAmount = (float) ($booking->paid_amount ?? 0);
            $dueAmount = max(0, (float) ($totalAmount - $discount - $paidAmount));

            $tests = $booking->items->map(function ($item) {
                return [
                    'item_id' => $item->id,
                    'test_id' => $item->lab_test_id,
                    'test_name' => $item->labTest?->name ?? 'N/A',
                    'category' => $item->labTest?->category ?? 'General',
                    'code' => $item->labTest?->code ?? '',
                    'price' => (float) ($item->price ?? 0),
                    'barcode' => $item->barcode ?? 'N/A',
                    'sample_type' => $item->sample_type ?? 'N/A',
                    'sample_status' => $item->sample_status ?? 'pending',
                ];
            });

            return [
                'id' => $booking->id,
                'invoice_number' => $booking->invoice_number,
                'booking_date' => $booking->created_at ? $booking->created_at->format('Y-m-d H:i:s') : 'N/A',
                'company' => [
                    'id' => $booking->company_id,
                    'name' => $booking->company?->name ?? 'N/A',
                    'phone' => $booking->company?->phone ?? 'N/A',
                ],
                'patient' => [
                    'id' => $booking->patient_id,
                    'name' => $booking->patient?->name ?? 'Walk-in Patient',
                    'phone' => $booking->patient?->phone ?? 'N/A',
                    'age' => $booking->patient?->age ?? 'N/A',
                    'gender' => $booking->patient?->gender ?? 'N/A',
                ],
                'payment' => [
                    'total_amount' => $totalAmount,
                    'discount' => $discount,
                    'net_amount' => max(0, $totalAmount - $discount),
                    'paid_amount' => $paidAmount,
                    'due_amount' => $dueAmount,
                    'payment_status' => $booking->payment_status ?? 'unpaid',
                    'booking_status' => $booking->status ?? 'pending',
                ],
                'total_tests_count' => $tests->count(),
                'tests' => $tests,
            ];
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Payments and tests fetched successfully.',
            'summary' => [
                'total_records' => $payments->count(),
                'total_collected' => (float) $payments->sum('payment.paid_amount'),
                'total_invoiced' => (float) $payments->sum('payment.total_amount'),
                'total_tests_conducted' => (int) $payments->sum('total_tests_count'),
            ],
            'data' => $payments,
        ], 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }
}
