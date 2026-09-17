<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\LabTest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LabTestManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_access_tests_index(): void
    {
        $company = Company::create(['name' => 'City Lab', 'email' => 'city@lab.com', 'status' => 'active']);
        $admin = User::factory()->create(['company_id' => $company->id, 'role' => 'super_admin']);

        $response = $this->actingAs($admin)->get('/admin/tests');

        $response->assertStatus(200);
        $response->assertSee('Test Management');
    }

    public function test_super_admin_can_create_test_with_parameters_and_notes(): void
    {
        $company = Company::create(['name' => 'City Lab', 'email' => 'city@lab.com', 'status' => 'active']);
        $admin = User::factory()->create(['company_id' => $company->id, 'role' => 'super_admin']);

        $response = $this->actingAs($admin)->post('/admin/tests', [
            'category' => 'Hematology',
            'name' => 'CBC Advanced',
            'code' => 'CBC-ADV',
            'price' => 750.00,
            'status' => 'active',
            'parameters' => [
                ['name' => 'WBC', 'unit' => '10^3/µl', 'normal_range_text' => '4.0-10.0', 'method' => 'Automated'],
                ['name' => 'RBC', 'unit' => '10^6/µl', 'normal_range_text' => '4.0-5.5', 'method' => 'Automated'],
            ],
            'notes' => [
                ['note_text' => 'Sample collected inside lab.'],
                ['note_text' => 'Electronically verified report.'],
            ],
        ]);

        $response->assertRedirect('/admin/tests');
        $this->assertDatabaseHas('lab_tests', ['name' => 'CBC Advanced', 'company_id' => $company->id]);
        $this->assertDatabaseHas('lab_test_parameters', ['name' => 'WBC', 'company_id' => $company->id]);
        $this->assertDatabaseHas('lab_test_notes', ['note_text' => 'Sample collected inside lab.', 'company_id' => $company->id]);
    }

    public function test_tenant_isolation_prevents_seeing_other_company_tests(): void
    {
        $company1 = Company::create(['name' => 'Lab 1', 'email' => 'lab1@lab.com', 'status' => 'active']);
        $admin1 = User::factory()->create(['company_id' => $company1->id, 'role' => 'super_admin']);
        $test1 = LabTest::create(['company_id' => $company1->id, 'category' => 'Hematology', 'name' => 'Lab 1 Secret Test', 'price' => 100]);

        $company2 = Company::create(['name' => 'Lab 2', 'email' => 'lab2@lab.com', 'status' => 'active']);
        $admin2 = User::factory()->create(['company_id' => $company2->id, 'role' => 'super_admin']);

        $response = $this->actingAs($admin2)->get('/admin/tests');

        $response->assertStatus(200);
        $response->assertDontSee('Lab 1 Secret Test');
    }
}
