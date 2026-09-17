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
        Schema::table('lab_test_parameters', function (Blueprint $table) {
            $table->string('male_range')->nullable()->after('normal_range_text');
            $table->string('female_range')->nullable()->after('male_range');
        });

        Schema::table('test_result_parameters', function (Blueprint $table) {
            $table->string('male_range')->nullable()->after('normal_range_text');
            $table->string('female_range')->nullable()->after('male_range');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lab_test_parameters', function (Blueprint $table) {
            $table->dropColumn(['male_range', 'female_range']);
        });

        Schema::table('test_result_parameters', function (Blueprint $table) {
            $table->dropColumn(['male_range', 'female_range']);
        });
    }
};
