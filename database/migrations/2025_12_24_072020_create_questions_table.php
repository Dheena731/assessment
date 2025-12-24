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
    Schema::create('questions', function (Blueprint $table) {
        $table->id();
        $table->text('question');
        $table->enum('type', ['mcq', 'short_answer', 'code'])->default('mcq');
        $table->text('hint')->nullable();
        $table->string('skill');
        $table->text('expected_format')->nullable();
        $table->text('ai_rubric')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
