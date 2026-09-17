<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Patient;
use App\Models\TestBooking;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class EarningsReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_view_earnings_report()
    {
        $company = Company::factory()->create();
        $admin = User::factory()->create(['company_id' => $company->id, 'role' => 'super_admin']);
        $patient = Patient::create([
            'company_id' => $company->id,
            'name' => 'Test Patient',
        ]);

        // Create some bookings
        TestBooking::create([
            'company_id' => $company->id,
            'patient_id' => $patient->id,
            'invoice_number' => 'INV-1',
            'total_amount' => 500,
            'paid_amount' => 500,
            'payment_status' => 'paid',
            'created_at' => Carbon::today(),
        ]);

        $booking2 = TestBooking::create([
            'company_id' => $company->id,
            'patient_id' => $patient->id,
            'invoice_number' => 'INV-2',
            'total_amount' => 1000,
            'paid_amount' => 500,
            'payment_status' => 'partial',
        ]);

        DB::table('test_bookings')
            ->where('id', $booking2->id)
            ->update(['created_at' => Carbon::today()->subDays(2)]);

        $response = $this->actingAs($admin)->get(route('admin.reports.earnings'));

        $response->assertStatus(200);
        $response->assertViewHas('todayTotal', 500.0);
        $response->assertViewHas('overallTotal', 1000.0);
    }
}
