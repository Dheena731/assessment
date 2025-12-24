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
    Schema::create('user_assessments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->foreignId('resume_id')->constrained()->cascadeOnDelete();
        $table->foreignId('assessment_id')->constrained('assessments');
        
        $table->json('selected_questions'); // [1,5,23...]
        $table->json('user_answers')->nullable(); // {"1":"B", "5":"docker run -d"}
        $table->integer('score')->default(0);
        $table->boolean('is_completed')->default(false);
        $table->timestamp('started_at')->nullable();
        $table->timestamp('ended_at')->nullable();
        
        $table->timestamps();
        $table->unique(['user_id', 'assessment_id']); // 1 attempt per assessment
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_assessments');
    }
};
