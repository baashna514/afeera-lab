<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LabTest;
use App\Models\Patient;
use App\Models\TestBooking;
use App\Models\TestBookingItem;
use App\Models\TestResult;
use App\Models\TestResultParameter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = TestBooking::with('patient')->latest()->paginate(15);

        return view('admin.bookings.index', compact('bookings'));
    }

    public function create()
    {
        $labTests = LabTest::where('status', 'active')->get();

        return view('admin.bookings.create', compact('labTests'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_name' => 'required|string|max:255',
            'patient_phone' => 'nullable|string|max:255',
            'patient_age' => 'nullable|integer',
            'patient_gender' => 'nullable|in:male,female,other',
            'test_ids' => 'required|array',
            'test_ids.*' => 'exists:lab_tests,id',
            'discount' => 'nullable|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
        ]);

        return DB::transaction(function () use ($request) {
            $patient = Patient::firstOrCreate(
                [
                    'phone' => $request->patient_phone ?? null,
                    'name' => $request->patient_name,
                ],
                [
                    'age' => $request->patient_age,
                    'gender' => $request->patient_gender,
                ]
            );

            $tests = LabTest::whereIn('id', $request->test_ids)->with('parameters')->get();
            $totalAmount = $tests->sum('price');
            $discount = $request->discount ?? 0;
            $paidAmount = $request->paid_amount ?? 0;

            $net = $totalAmount - $discount;
            if ($paidAmount >= $net) {
                $paymentStatus = 'paid';
            } elseif ($paidAmount > 0) {
                $paymentStatus = 'partial';
            } else {
                $paymentStatus = 'unpaid';
            }

            $booking = TestBooking::create([
                'patient_id' => $patient->id,
                'invoice_number' => 'INV-'.time().'-'.rand(100, 999),
                'total_amount' => $totalAmount,
                'discount' => $discount,
                'paid_amount' => $paidAmount,
                'payment_status' => $paymentStatus,
                'status' => 'pending',
            ]);

            foreach ($tests as $test) {
                $sampleType = match ($test->category) {
                    'Hematology' => 'EDTA Whole Blood',
                    'Serology Test' => 'Serum',
                    'Biochemistry' => 'Fluoride / Serum',
                    'Microbiology' => 'Swab / Culture Specimen',
                    'Urinalysis' => 'Urine Sample',
                    default => 'Blood / Serum',
                };

                $item = TestBookingItem::create([
                    'test_booking_id' => $booking->id,
                    'lab_test_id' => $test->id,
                    'price' => $test->price,
                    'barcode' => 'SMP-'.date('ymd').'-'.rand(1000, 9999),
                    'sample_type' => $sampleType,
                    'sample_status' => 'pending',
                ]);

                $result = TestResult::create([
                    'test_booking_item_id' => $item->id,
                    'status' => 'pending',
                ]);

                $patientGender = strtolower($patient->gender ?? '');

                foreach ($test->parameters as $param) {
                    $assignedRange = $param->normal_range_text;
                    if ($patientGender === 'male' && ! empty($param->male_range)) {
                        $assignedRange = $param->male_range;
                    } elseif ($patientGender === 'female' && ! empty($param->female_range)) {
                        $assignedRange = $param->female_range;
                    }

                    TestResultParameter::create([
                        'test_result_id' => $result->id,
                        'parameter_name' => $param->name,
                        'unit' => $param->unit,
                        'normal_range_text' => $assignedRange,
                        'male_range' => $param->male_range,
                        'female_range' => $param->female_range,
                        'sort_order' => $param->sort_order,
                        'result_value' => $param->default_value,
                    ]);
                }
            }

            return redirect()->route('admin.bookings.index')->with('success', 'Booking created successfully!');
        });
    }
}
