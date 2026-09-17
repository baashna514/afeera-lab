<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\LabTest;
use App\Models\LabTestParameter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PatientBookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_create_patient_and_booking_with_result_templates()
    {
        $company = Company::factory()->create();
        $admin = User::factory()->create(['company_id' => $company->id, 'role' => 'super_admin']);

        // Create a test in the catalog
        $test = LabTest::create([
            'company_id' => $company->id,
            'category' => 'Hematology',
            'name' => 'Complete Blood Count',
            'price' => 1000,
        ]);

        $param = LabTestParameter::create([
            'company_id' => $company->id,
            'lab_test_id' => $test->id,
            'name' => 'WBC',
            'unit' => '10^3/uL',
            'normal_range_text' => '4.0 - 10.0',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.bookings.store'), [
            'patient_name' => 'John Doe',
            'patient_phone' => '123456789',
            'patient_age' => 30,
            'patient_gender' => 'male',
            'test_ids' => [$test->id],
            'discount' => 100,
            'paid_amount' => 900,
        ]);

        $response->assertRedirect(route('admin.bookings.index'));

        $this->assertDatabaseHas('patients', [
            'name' => 'John Doe',
            'phone' => '123456789',
        ]);

        $this->assertDatabaseHas('test_bookings', [
            'total_amount' => 1000,
            'discount' => 100,
            'paid_amount' => 900,
            'payment_status' => 'paid',
        ]);

        $this->assertDatabaseHas('test_booking_items', [
            'lab_test_id' => $test->id,
            'price' => 1000,
        ]);

        $this->assertDatabaseHas('test_results', [
            'status' => 'pending',
        ]);

        // Validate result template parameter was correctly copied
        $this->assertDatabaseHas('test_result_parameters', [
            'parameter_name' => 'WBC',
            'unit' => '10^3/uL',
            'normal_range_text' => '4.0 - 10.0',
        ]);
    }
}
