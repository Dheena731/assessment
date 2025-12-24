<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('resumes', function (Blueprint $table) {
            $table->integer('skill_score')->default(0)->after('is_valid');
            $table->integer('threshold_score')->default(50)->after('skill_score');
            $table->json('matched_skills')->nullable()->after('threshold_score');
        });
    }

    public function down(): void
    {
        Schema::table('resumes', function (Blueprint $table) {
            $table->dropColumn(['skill_score', 'threshold_score', 'matched_skills']);
        });
    }
};
