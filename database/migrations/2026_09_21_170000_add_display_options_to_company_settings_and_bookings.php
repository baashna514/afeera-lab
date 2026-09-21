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
            // Header display options
            $table->boolean('show_header_logo')->default(true)->after('show_header');
            $table->boolean('show_header_company_name')->default(true)->after('show_header_logo');
            $table->boolean('show_header_address')->default(true)->after('show_header_company_name');
            $table->boolean('show_header_phone')->default(true)->after('show_header_address');
            $table->boolean('show_header_email')->default(true)->after('show_header_phone');
            $table->boolean('show_header_lab_no')->default(true)->after('show_header_email');
            $table->boolean('show_header_report_date')->default(true)->after('show_header_lab_no');
            $table->boolean('show_header_sample_date')->default(true)->after('show_header_report_date');
            $table->boolean('show_header_report_status')->default(true)->after('show_header_sample_date');

            // Patient display options
            $table->boolean('show_patient_name')->default(true)->after('show_patient_info');
            $table->boolean('show_patient_age_gender')->default(true)->after('show_patient_name');
            $table->boolean('show_patient_id')->default(true)->after('show_patient_age_gender');
            $table->boolean('show_patient_referred_by')->default(true)->after('show_patient_id');
            $table->boolean('show_patient_contact')->default(true)->after('show_patient_referred_by');
            $table->boolean('show_patient_collection_type')->default(true)->after('show_patient_contact');
            $table->boolean('show_patient_fasting')->default(true)->after('show_patient_collection_type');
            $table->boolean('show_patient_clinical_info')->default(true)->after('show_patient_fasting');
            $table->boolean('show_patient_barcode')->default(true)->after('show_patient_clinical_info');

            // Footer display options
            $table->boolean('show_footer_qr')->default(true)->after('show_footer');
            $table->boolean('show_footer_signature')->default(true)->after('show_footer_qr');
            $table->boolean('show_footer_doctor_name')->default(true)->after('show_footer_signature');
            $table->boolean('show_footer_doctor_degree')->default(true)->after('show_footer_doctor_name');
            $table->boolean('show_footer_doctor_reg')->default(true)->after('show_footer_doctor_degree');
            $table->boolean('show_footer_disclaimer')->default(true)->after('show_footer_doctor_reg');
        });

        Schema::table('test_bookings', function (Blueprint $table) {
            $table->string('lab_number')->nullable()->after('invoice_number');
            $table->string('referred_by')->nullable()->after('lab_number');
            $table->string('collection_type')->nullable()->after('referred_by');
            $table->string('fasting')->nullable()->after('collection_type');
            $table->text('clinical_info')->nullable()->after('fasting');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('company_settings', function (Blueprint $table) {
            $table->dropColumn([
                'show_header_logo',
                'show_header_company_name',
                'show_header_address',
                'show_header_phone',
                'show_header_email',
                'show_header_lab_no',
                'show_header_report_date',
                'show_header_sample_date',
                'show_header_report_status',
                'show_patient_name',
                'show_patient_age_gender',
                'show_patient_id',
                'show_patient_referred_by',
                'show_patient_contact',
                'show_patient_collection_type',
                'show_patient_fasting',
                'show_patient_clinical_info',
                'show_patient_barcode',
                'show_footer_qr',
                'show_footer_signature',
                'show_footer_doctor_name',
                'show_footer_doctor_degree',
                'show_footer_doctor_reg',
                'show_footer_disclaimer',
            ]);
        });

        Schema::table('test_bookings', function (Blueprint $table) {
            $table->dropColumn([
                'lab_number',
                'referred_by',
                'collection_type',
                'fasting',
                'clinical_info',
            ]);
        });
    }
};
