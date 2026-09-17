<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TestBooking;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PaymentApiController extends Controller
{
    /**
     * Login and get API token.
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials.',
            ], 401);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful.',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
        ]);
    }

    /**
     * Logout and revoke current token.
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully.',
        ]);
    }

    /**
     * Simple test endpoint to verify API is working.
     */
    public function test(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'API is working!',
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    /**
     * Get payments/earnings data with date filtering.
     *
     * Returns daily summary: date, test_count, total_amount
     * Filters: from_date, to_date
     */
    public function payments(Request $request): JsonResponse
    {
        $request->validate([
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
        ]);

        $user = $request->user();

        $query = TestBooking::query()
            ->where('payment_status', 'paid');

        // If user belongs to a company, scope to that company
        if ($user->company_id) {
            $query->where('company_id', $user->company_id);
        }

        // Date filter
        if ($request->from_date) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->to_date) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // Daily summary
        $dailySummary = (clone $query)
            ->selectRaw('DATE(created_at) as payment_date')
            ->selectRaw('COUNT(*) as test_count')
            ->selectRaw('SUM(paid_amount) as total_amount')
            ->selectRaw('SUM(discount) as total_discount')
            ->groupByRaw('DATE(created_at)')
            ->orderByRaw('DATE(created_at) DESC')
            ->get();

        // Overall totals
        $totals = (clone $query)->selectRaw(
            'COUNT(*) as total_tests, SUM(paid_amount) as total_earned, SUM(discount) as total_discount'
        )->first();

        // Detailed bookings list
        $bookings = (clone $query)
            ->with('patient:id,name,phone')
            ->select('id', 'patient_id', 'invoice_number', 'total_amount', 'discount', 'paid_amount', 'payment_status', 'status', 'created_at')
            ->latest()
            ->limit(100)
            ->get()
            ->map(fn ($b) => [
                'id' => $b->id,
                'invoice_number' => $b->invoice_number,
                'patient_name' => $b->patient?->name,
                'patient_phone' => $b->patient?->phone,
                'total_amount' => $b->total_amount,
                'discount' => $b->discount,
                'paid_amount' => $b->paid_amount,
                'payment_status' => $b->payment_status,
                'status' => $b->status,
                'date' => $b->created_at->format('Y-m-d'),
            ]);

        return response()->json([
            'success' => true,
            'data' => [
                'summary' => [
                    'total_tests' => (int) ($totals->total_tests ?? 0),
                    'total_earned' => (float) ($totals->total_earned ?? 0),
                    'total_discount' => (float) ($totals->total_discount ?? 0),
                ],
                'daily_breakdown' => $dailySummary->map(fn ($day) => [
                    'date' => $day->payment_date,
                    'test_count' => (int) $day->test_count,
                    'total_amount' => (float) $day->total_amount,
                    'total_discount' => (float) $day->total_discount,
                ]),
                'bookings' => $bookings,
            ],
            'filters_applied' => [
                'from_date' => $request->from_date,
                'to_date' => $request->to_date,
            ],
        ]);
    }
}
