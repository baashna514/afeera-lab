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
        Schema::create('company_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();

            // Company Info
            $table->string('company_name')->nullable();
            $table->string('company_email')->nullable();
            $table->string('company_phone')->nullable();
            $table->text('company_address')->nullable();
            $table->string('company_logo')->nullable(); // path to logo image

            // Report Display Settings (Hide/Show)
            $table->boolean('show_header')->default(true);
            $table->boolean('show_patient_info')->default(true);
            $table->boolean('show_footer')->default(true);

            // Doctor Info
            $table->string('doctor_name')->nullable();
            $table->string('doctor_degree')->nullable();
            $table->string('doctor_reg_no')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_settings');
    }
};
