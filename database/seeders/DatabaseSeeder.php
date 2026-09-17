<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\LabTest;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create SaaS Owner (Single Owner)
        User::updateOrCreate(
            ['email' => 'owner@gmail.com'],
            [
                'name' => 'SaaS System Owner',
                'password' => Hash::make('password'),
                'role' => 'owner',
                'company_id' => null,
                'phone' => '+92 300 0000000',
                'is_active' => true,
            ]
        );

        // Create Demo Company 1
        $company = Company::updateOrCreate(
            ['email' => 'info@baashna.com'],
            [
                'name' => 'Siyaal Surgical Hospital & Labs',
                'phone' => '+92 301 0417383',
                'address' => 'Near NRSP Bank, FatehPur Road Karor Lal Esan',
                'status' => 'active',
            ]
        );

        // Create Company Super Admin
        User::updateOrCreate(
            ['email' => 'superadmin@gmail.com'],
            [
                'company_id' => $company->id,
                'name' => 'Dr. Naeem Abbas Siyal',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
                'phone' => '+92 301 0417383',
                'is_active' => true,
            ]
        );

        // Seed Real Hospital Tests for Company 1
        $this->seedTestsForCompany($company->id);
    }

    private function seedTestsForCompany(int $companyId): void
    {
        // 1. CBC Test
        $cbc = LabTest::updateOrCreate(
            ['company_id' => $companyId, 'code' => 'CBC'],
            [
                'category' => 'Hematology',
                'name' => 'Complete Blood Count (CBC)',
                'price' => 600.00,
                'status' => 'active',
            ]
        );

        $cbcParameters = [
            ['name' => 'WBC', 'unit' => '10^3/µl', 'normal_range_text' => '4.0-10.0', 'male_range' => '4.0-11.0', 'female_range' => '4.0-10.0', 'sort_order' => 1],
            ['name' => 'Lymphocytes(Lym%)', 'unit' => '%', 'normal_range_text' => '20.0 - 40.0', 'male_range' => '20.0 - 40.0', 'female_range' => '20.0 - 40.0', 'sort_order' => 2],
            ['name' => 'Monocytes(Mid%)', 'unit' => '%', 'normal_range_text' => '3.0-14.0', 'male_range' => '3.0-14.0', 'female_range' => '3.0-14.0', 'sort_order' => 3],
            ['name' => 'Neutrophils(Gran%)', 'unit' => '%', 'normal_range_text' => '50.0 - 70.0', 'male_range' => '50.0 - 70.0', 'female_range' => '50.0 - 70.0', 'sort_order' => 4],
            ['name' => 'Eosinophils(Gran#)', 'unit' => '10^3/µl', 'normal_range_text' => '2.00-7.00', 'male_range' => '2.00-7.00', 'female_range' => '2.00-7.00', 'sort_order' => 5],
            ['name' => 'RBC', 'unit' => '10^6/µl', 'normal_range_text' => '4.00-5.50', 'male_range' => '4.50-5.90', 'female_range' => '4.00-5.20', 'sort_order' => 6],
            ['name' => 'HGB (Hemoglobin)', 'unit' => 'g/dl', 'normal_range_text' => '12.0-17.5', 'male_range' => '13.5-17.5', 'female_range' => '12.0-15.5', 'sort_order' => 7],
            ['name' => 'MCHC', 'unit' => 'g/dl', 'normal_range_text' => '32.0-36.0', 'male_range' => '32.0-36.0', 'female_range' => '32.0-36.0', 'sort_order' => 8],
            ['name' => 'MCH', 'unit' => 'pg', 'normal_range_text' => '27.0-34.0', 'male_range' => '27.0-34.0', 'female_range' => '27.0-34.0', 'sort_order' => 9],
            ['name' => 'MCV', 'unit' => 'fl', 'normal_range_text' => '80.0-100.0', 'male_range' => '80.0-100.0', 'female_range' => '80.0-100.0', 'sort_order' => 10],
            ['name' => 'HCT', 'unit' => '%', 'normal_range_text' => '36.0-53.0', 'male_range' => '41.0-53.0', 'female_range' => '36.0-46.0', 'sort_order' => 11],
            ['name' => 'PLT(Platelet Count)', 'unit' => '10^3/µl', 'normal_range_text' => '150.0-450.0', 'male_range' => '150.0-450.0', 'female_range' => '150.0-450.0', 'sort_order' => 12],
        ];

        foreach ($cbcParameters as $p) {
            $cbc->parameters()->updateOrCreate(
                ['name' => $p['name']],
                array_merge($p, ['company_id' => $companyId])
            );
        }

        // 2. Serology Test (HCV-HBSAG-HIV-BSR)
        $serology = LabTest::updateOrCreate(
            ['company_id' => $companyId, 'code' => 'SEROLOGY'],
            [
                'category' => 'Serology Test',
                'name' => 'HCV-HBSAG-HIV-BSR',
                'price' => 1200.00,
                'status' => 'active',
            ]
        );

        $serologyParameters = [
            ['name' => 'Blood Anti HBsAg', 'method' => 'By Rapid Method', 'default_value' => 'NEGATIVE', 'sort_order' => 1],
            ['name' => 'Anti HCV', 'method' => 'By Rapid Method', 'default_value' => 'NEGATIVE', 'sort_order' => 2],
            ['name' => 'ANTI HIV', 'method' => 'By Rapid Method', 'default_value' => 'NEGATIVE', 'sort_order' => 3],
            ['name' => 'Randum Sugar', 'unit' => 'mg/dl', 'normal_range_text' => '80------160', 'sort_order' => 4],
        ];

        foreach ($serologyParameters as $p) {
            $serology->parameters()->updateOrCreate(
                ['name' => $p['name']],
                array_merge($p, ['company_id' => $companyId])
            );
        }

        // 3. Blood Cross Match (BCM) Test
        $bcm = LabTest::updateOrCreate(
            ['company_id' => $companyId, 'code' => 'BCM'],
            [
                'category' => 'Serology Test',
                'name' => 'Blood Cross Match',
                'price' => 1500.00,
                'status' => 'active',
            ]
        );

        $bcmParameters = [
            ['name' => 'Recipient Blood group', 'default_value' => 'A+VE', 'sort_order' => 1],
            ['name' => 'Donor Blood group', 'default_value' => 'A+VE', 'sort_order' => 2],
            ['name' => 'HBs Ag (screening test)', 'default_value' => 'Non Reactive', 'sort_order' => 3],
            ['name' => 'HCV (screening test)', 'default_value' => 'Non Reactive', 'sort_order' => 4],
            ['name' => 'HIV (screening test)', 'default_value' => 'Non Reactive', 'sort_order' => 5],
            ['name' => 'Syphilis (screening test)', 'default_value' => 'Non Reactive', 'sort_order' => 6],
        ];

        foreach ($bcmParameters as $p) {
            $bcm->parameters()->updateOrCreate(
                ['name' => $p['name']],
                array_merge($p, ['company_id' => $companyId])
            );
        }

        $bcmNotes = [
            'Cross match is performed by major method,',
            'Including 3steps,Saline phase, bovine Phase and Cooms Phase.',
            'There is no agglutination seen in any phase.',
            'Blood is compatible FOR SAFE TRANSFUSION.',
            'نوٹ: کراس میچنگ تسلی بخش ہے لیکن میچ کراس میچنگ کچھ ردعمل ہو سکتا ہے اس لیے پہلے چند منٹ بلڈ کی نگرانی کریں اور باقی بلڈ کا باقاعدہ معائنہ کریں۔',
        ];

        foreach ($bcmNotes as $idx => $note) {
            $bcm->notes()->updateOrCreate(
                ['note_text' => $note],
                ['company_id' => $companyId, 'sort_order' => $idx + 1]
            );
        }

        // 4. RBS (Random Blood Sugar)
        $rbs = LabTest::updateOrCreate(
            ['company_id' => $companyId, 'code' => 'RBS'],
            [
                'category' => 'Biochemistry',
                'name' => 'Random Blood Sugar (RBS)',
                'price' => 300.00,
                'status' => 'active',
            ]
        );

        $rbsParameters = [
            ['name' => 'Fasting Sugar', 'unit' => 'mg/dl', 'normal_range_text' => '70------110', 'sort_order' => 1],
            ['name' => 'Randum Sugar', 'unit' => 'mg/dl', 'normal_range_text' => '80------160', 'sort_order' => 2],
        ];

        foreach ($rbsParameters as $p) {
            $rbs->parameters()->updateOrCreate(
                ['name' => $p['name']],
                array_merge($p, ['company_id' => $companyId])
            );
        }
    }
}
