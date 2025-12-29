<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('resume_id')->constrained()->cascadeOnDelete();
            $table->foreignId('assessment_id')->constrained('assessments');
            $table->unsignedInteger('total_duration_seconds')->default(900);
            $table->json('selected_questions');
            $table->json('user_answers')->nullable();
            $table->json('violations')->nullable();  // ✅ NO after()
            $table->integer('violation_count')->default(0);  // ✅ NO after()
            $table->integer('score')->default(0);
            $table->boolean('is_completed')->default(false);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'assessment_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_assessments');
    }
};
