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
        Schema::create('skills', function (Blueprint $table) {
    $table->id();

    // Display name
    $table->string('name');              // e.g. "Python"

    // Used for matching (lowercase, no spaces)
    $table->string('normalized_name')->unique(); // e.g. "python"

    // Scoring
    $table->integer('weight')->default(0); // e.g. 30

    // Control flag
    $table->boolean('is_active')->default(true);

    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skills');
    }
};
