<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('test_result_parameters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_result_id')->constrained('test_results')->cascadeOnDelete();
            $table->string('parameter_name');
            $table->string('unit')->nullable();
            $table->string('normal_range_text')->nullable();
            $table->string('result_value')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('test_result_parameters');
    }
};
