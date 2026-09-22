<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('company_settings', function (Blueprint $table) {
            $table->string('default_lab_prefix')->nullable()->default('INV-')->after('show_header_report_status');
            $table->string('default_referred_by')->nullable()->default('Dr. Consultant Physician')->after('show_patient_barcode');
            $table->string('default_collection_type')->nullable()->default('Venous Blood')->after('default_referred_by');
            $table->string('default_fasting')->nullable()->default('No')->after('default_collection_type');
            $table->string('default_clinical_info')->nullable()->default('Routine Check-up')->after('default_fasting');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('company_settings', function (Blueprint $table) {
            $table->dropColumn([
                'default_lab_prefix',
                'default_referred_by',
                'default_collection_type',
                'default_fasting',
                'default_clinical_info',
            ]);
        });
    }
};
