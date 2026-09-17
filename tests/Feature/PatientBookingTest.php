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

    public function test_booking_applies_gender_specific_normal_ranges_based_on_patient_gender()
    {
        $company = Company::factory()->create();
        $admin = User::factory()->create(['company_id' => $company->id, 'role' => 'super_admin']);

        $test = LabTest::create([
            'company_id' => $company->id,
            'category' => 'Hematology',
            'name' => 'Hemoglobin Test',
            'price' => 500,
        ]);

        $param = LabTestParameter::create([
            'company_id' => $company->id,
            'lab_test_id' => $test->id,
            'name' => 'HGB',
            'unit' => 'g/dl',
            'normal_range_text' => '12.0 - 17.5',
            'male_range' => '13.5 - 17.5',
            'female_range' => '12.0 - 15.5',
        ]);

        // 1. Male Patient Booking
        $this->actingAs($admin)->post(route('admin.bookings.store'), [
            'patient_name' => 'Ali Khan',
            'patient_gender' => 'male',
            'test_ids' => [$test->id],
        ]);

        $this->assertDatabaseHas('test_result_parameters', [
            'parameter_name' => 'HGB',
            'normal_range_text' => '13.5 - 17.5', // Automatically uses Male range
        ]);

        // 2. Female Patient Booking
        $this->actingAs($admin)->post(route('admin.bookings.store'), [
            'patient_name' => 'Fatima Bibi',
            'patient_gender' => 'female',
            'test_ids' => [$test->id],
        ]);

        $this->assertDatabaseHas('test_result_parameters', [
            'parameter_name' => 'HGB',
            'normal_range_text' => '12.0 - 15.5', // Automatically uses Female range
        ]);
    }
}
