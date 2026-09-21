<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\CompanySetting;
use App\Models\LabTest;
use App\Models\Patient;
use App\Models\TestBooking;
use App\Models\TestBookingItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ReportDisplaySettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_settings_page_renders_with_3_display_sections()
    {
        $company = Company::factory()->create();
        $admin = User::factory()->create(['company_id' => $company->id, 'role' => 'super_admin']);

        $response = $this->actingAs($admin)->get(route('admin.settings.index'));

        $response->assertStatus(200);
        $response->assertSee('1. Header Display Settings');
        $response->assertSee('2. Patient Information Display Settings');
        $response->assertSee('3. Footer Display Settings');
    }

    public function test_super_admin_can_save_all_display_settings_and_sub_options()
    {
        $company = Company::factory()->create();
        $admin = User::factory()->create(['company_id' => $company->id, 'role' => 'super_admin']);

        $response = $this->actingAs($admin)->post(route('admin.settings.store'), [
            'company_name' => 'Siyal Surgical Hospital',
            'company_email' => 'siyal@example.com',
            'company_phone' => '0301-0417383',
            'show_header' => '1',
            'show_header_logo' => '1',
            'show_header_company_name' => '1',
            'show_header_address' => '1',
            'show_header_phone' => '1',
            'show_header_email' => '1',
            'show_header_lab_no' => '1',
            'show_header_report_date' => '1',
            'show_header_sample_date' => '1',
            'show_header_report_status' => '1',
            'show_patient_info' => '1',
            'show_patient_name' => '1',
            'show_patient_age_gender' => '1',
            'show_patient_id' => '1',
            'show_patient_referred_by' => '1',
            'show_patient_contact' => '1',
            'show_patient_collection_type' => '1',
            'show_patient_fasting' => '1',
            'show_patient_clinical_info' => '1',
            'show_patient_barcode' => '1',
            'show_footer' => '1',
            'show_footer_qr' => '1',
            'show_footer_signature' => '1',
            'show_footer_doctor_name' => '1',
            'show_footer_doctor_degree' => '1',
            'show_footer_doctor_reg' => '1',
            'show_footer_disclaimer' => '1',
            'default_lab_prefix' => 'LAB-',
            'default_referred_by' => 'Dr. Farooq',
            'default_collection_type' => 'Serum',
            'default_fasting' => '12 Hours Fasting',
            'default_clinical_info' => 'Diabetes Routine Monitoring',
            'doctor_name' => 'Dr. Ritu Malhotra',
            'doctor_degree' => 'MD (Pathology)',
            'doctor_reg_no' => 'DMC/2009/12345',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('company_settings', [
            'company_id' => $company->id,
            'company_name' => 'Siyal Surgical Hospital',
            'default_lab_prefix' => 'LAB-',
            'default_referred_by' => 'Dr. Farooq',
            'default_collection_type' => 'Serum',
            'default_fasting' => '12 Hours Fasting',
            'default_clinical_info' => 'Diabetes Routine Monitoring',
            'show_header' => true,
            'show_header_logo' => true,
            'show_patient_info' => true,
            'show_patient_fasting' => true,
            'show_patient_clinical_info' => true,
            'show_footer' => true,
            'doctor_name' => 'Dr. Ritu Malhotra',
        ]);
    }

    public function test_super_admin_can_upload_and_remove_logo()
    {
        Storage::fake('public');

        $company = Company::factory()->create();
        $admin = User::factory()->create(['company_id' => $company->id, 'role' => 'super_admin']);

        // Upload logo
        $file = UploadedFile::fake()->image('hospital_logo.png', 100, 100);

        $response = $this->actingAs($admin)->post(route('admin.settings.store'), [
            'company_name' => 'Test Lab',
            'company_logo' => $file,
            'show_header' => '1',
            'show_patient_info' => '1',
            'show_footer' => '1',
        ]);

        $response->assertRedirect();
        $setting = CompanySetting::where('company_id', $company->id)->first();
        $this->assertNotNull($setting->company_logo);
        Storage::disk('public')->assertExists($setting->company_logo);

        // Remove logo via removeLogo route
        $deleteResponse = $this->actingAs($admin)->delete(route('admin.settings.remove-logo'));
        $deleteResponse->assertRedirect();

        $setting->refresh();
        $this->assertNull($setting->company_logo);
    }

    public function test_booking_stores_clinical_and_sample_fields()
    {
        $company = Company::factory()->create();
        $admin = User::factory()->create(['company_id' => $company->id, 'role' => 'super_admin']);

        $test = LabTest::create([
            'company_id' => $company->id,
            'category' => 'Hematology',
            'name' => 'Complete Blood Count (CBC)',
            'price' => 1500,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.bookings.store'), [
            'patient_name' => 'Moin Abbas',
            'patient_phone' => '03321773514',
            'patient_age' => 34,
            'patient_gender' => 'male',
            'lab_number' => 'LAB-2026-0099',
            'referred_by' => 'Dr. Farooq Ahmed',
            'collection_type' => 'Venous Blood',
            'fasting' => 'Yes (10-12 Hours)',
            'clinical_info' => 'Pre-operative evaluation',
            'test_ids' => [$test->id],
            'discount' => 0,
            'paid_amount' => 1500,
        ]);

        $response->assertRedirect(route('admin.bookings.index'));

        $this->assertDatabaseHas('test_bookings', [
            'lab_number' => 'LAB-2026-0099',
            'referred_by' => 'Dr. Farooq Ahmed',
            'collection_type' => 'Venous Blood',
            'fasting' => 'Yes (10-12 Hours)',
            'clinical_info' => 'Pre-operative evaluation',
        ]);
    }

    public function test_report_view_reflects_settings_and_dynamic_values()
    {
        $company = Company::factory()->create();
        $admin = User::factory()->create(['company_id' => $company->id, 'role' => 'super_admin']);

        // Set up custom company settings with some fields hidden
        CompanySetting::create([
            'company_id' => $company->id,
            'company_name' => 'Siyal Surgical Hospital',
            'show_header' => true,
            'show_header_logo' => true,
            'show_header_company_name' => true,
            'show_header_address' => true,
            'show_header_phone' => true,
            'show_header_email' => true,
            'show_header_lab_no' => true,
            'show_header_report_date' => true,
            'show_header_sample_date' => true,
            'show_header_report_status' => true,
            'show_patient_info' => true,
            'show_patient_name' => true,
            'show_patient_age_gender' => true,
            'show_patient_id' => true,
            'show_patient_referred_by' => true,
            'show_patient_contact' => true,
            'show_patient_collection_type' => true,
            'show_patient_fasting' => false, // Hidden!
            'show_patient_clinical_info' => true,
            'show_patient_barcode' => true,
            'show_footer' => true,
            'show_footer_qr' => true,
            'show_footer_signature' => true,
            'show_footer_doctor_name' => true,
            'show_footer_doctor_degree' => true,
            'show_footer_doctor_reg' => true,
            'show_footer_disclaimer' => true,
            'doctor_name' => 'Dr. Ritu Malhotra',
            'doctor_degree' => 'MD (Pathology)',
            'doctor_reg_no' => 'DMC/2009/12345',
        ]);

        $patient = Patient::create([
            'company_id' => $company->id,
            'name' => 'Moin Abbas',
            'phone' => '03321773514',
            'age' => 34,
            'gender' => 'male',
        ]);

        $booking = TestBooking::create([
            'company_id' => $company->id,
            'patient_id' => $patient->id,
            'invoice_number' => 'INV-1789986126-273',
            'lab_number' => 'INV-1789986126-273',
            'referred_by' => 'Dr. Specialist',
            'collection_type' => 'Venous Blood',
            'fasting' => 'No',
            'clinical_info' => 'Routine Check-up',
            'total_amount' => 500,
            'status' => 'completed',
        ]);

        $test = LabTest::create([
            'company_id' => $company->id,
            'category' => 'Hematology',
            'name' => 'CBC',
            'price' => 500,
        ]);

        TestBookingItem::create([
            'company_id' => $company->id,
            'test_booking_id' => $booking->id,
            'lab_test_id' => $test->id,
            'price' => 500,
            'barcode' => 'SMP-260921-2913',
            'sample_status' => 'collected',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.reviews.report', $booking));

        $response->assertStatus(200);
        $response->assertSee('Siyal Surgical Hospital');
        $response->assertSee('Moin Abbas');
        $response->assertSee('Dr. Specialist');
        $response->assertSee('Dr. Ritu Malhotra');
        // Fasting should NOT be shown because show_patient_fasting = false
        $response->assertDontSee('Fasting');
    }
}
