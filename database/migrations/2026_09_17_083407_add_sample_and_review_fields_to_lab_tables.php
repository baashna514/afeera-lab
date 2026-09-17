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
        Schema::table('test_booking_items', function (Blueprint $table) {
            $table->string('barcode')->nullable()->after('price');
            $table->string('sample_type')->nullable()->default('Whole Blood')->after('barcode');
            $table->string('sample_status')->default('pending')->after('sample_type'); // pending, collected
            $table->timestamp('collected_at')->nullable()->after('sample_status');
        });

        Schema::table('test_results', function (Blueprint $table) {
            $table->foreignId('verified_by')->nullable()->after('status')->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable()->after('verified_by');
            $table->text('remarks')->nullable()->after('verified_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('test_booking_items', function (Blueprint $table) {
            $table->dropColumn(['barcode', 'sample_type', 'sample_status', 'collected_at']);
        });

        Schema::table('test_results', function (Blueprint $table) {
            $table->dropConstrainedForeignId('verified_by');
            $table->dropColumn(['verified_at', 'remarks']);
        });
    }
};
