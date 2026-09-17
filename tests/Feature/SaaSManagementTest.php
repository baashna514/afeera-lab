<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SaaSManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_access_owner_dashboard(): void
    {
        $owner = User::factory()->create([
            'role' => 'owner',
            'company_id' => null,
        ]);

        $response = $this->actingAs($owner)->get('/owner/dashboard');

        $response->assertStatus(200);
        $response->assertSee('SaaS Overview');
    }

    public function test_owner_can_create_company_and_super_admin(): void
    {
        $owner = User::factory()->create([
            'role' => 'owner',
            'company_id' => null,
        ]);

        $response = $this->actingAs($owner)->post('/owner/companies', [
            'company_name' => 'Metro Diagnostic Lab',
            'company_email' => 'contact@metrolab.com',
            'company_phone' => '+92 300 1112233',
            'company_address' => 'Main Boulevard, Lahore',
            'status' => 'active',
            'admin_name' => 'Dr. Ali Raza',
            'admin_email' => 'ali@metrolab.com',
            'admin_password' => 'password123',
            'admin_phone' => '+92 300 4455667',
        ]);

        $response->assertRedirect('/owner/companies');
        $this->assertDatabaseHas('companies', ['email' => 'contact@metrolab.com']);
        $this->assertDatabaseHas('users', ['email' => 'ali@metrolab.com', 'role' => 'super_admin']);
    }

    public function test_company_super_admin_is_redirected_to_admin_dashboard(): void
    {
        $company = Company::create([
            'name' => 'Test Lab',
            'email' => 'test@lab.com',
            'status' => 'active',
        ]);

        $admin = User::factory()->create([
            'company_id' => $company->id,
            'role' => 'super_admin',
        ]);

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Laboratory Super Admin Dashboard');
        $response->assertSee('Test Lab');
    }

    public function test_non_owner_cannot_access_owner_dashboard(): void
    {
        $company = Company::create([
            'name' => 'Test Lab',
            'email' => 'test@lab.com',
            'status' => 'active',
        ]);

        $admin = User::factory()->create([
            'company_id' => $company->id,
            'role' => 'super_admin',
        ]);

        $response = $this->actingAs($admin)->get('/owner/dashboard');

        $response->assertRedirect('/admin/dashboard');
    }
}
