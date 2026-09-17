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
        Schema::create('lab_tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->string('category')->default('General');
            $table->string('name');
            $table->string('code')->nullable();
            $table->decimal('price', 10, 2)->default(0.00);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });

        Schema::create('lab_test_parameters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('lab_test_id')->constrained('lab_tests')->cascadeOnDelete();
            $table->string('name');
            $table->string('unit')->nullable();
            $table->string('min_range')->nullable();
            $table->string('max_range')->nullable();
            $table->string('normal_range_text')->nullable();
            $table->string('method')->nullable();
            $table->string('default_value')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('lab_test_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('lab_test_id')->constrained('lab_tests')->cascadeOnDelete();
            $table->text('note_text');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lab_test_notes');
        Schema::dropIfExists('lab_test_parameters');
        Schema::dropIfExists('lab_tests');
    }
};
