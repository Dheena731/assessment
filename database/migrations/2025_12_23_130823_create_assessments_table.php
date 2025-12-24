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
        Schema::create('assessments', function (Blueprint $table) {
        $table->id();

        // Basic info
        $table->string('name');              // "Backend Developer Assessment"
        $table->text('description')->nullable();

        // Eligibility rules
        $table->integer('threshold_score');  // Minimum score to access
        $table->boolean('is_active')->default(true);

        // Assessment behavior
        $table->integer('duration_minutes')->default(30); // Time limit
        $table->integer('pass_percentage')->default(60);  // For result evaluation

        $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessments');
    }
};

