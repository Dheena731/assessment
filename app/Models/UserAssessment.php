<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAssessment extends Model
{
    protected $fillable = [
        'user_id', 
        'resume_id', 
        'assessment_id',
        'total_duration_seconds',
        'selected_questions', 
        'user_answers', 
        'violations',           // ✅ ADDED
        'violation_count',      // ✅ ADDED
        'score',
        'is_completed', 
        'started_at', 
        'ended_at',
    ];

    protected $casts = [
        'selected_questions' => 'array',
        'user_answers' => 'array',
        'violations' => 'array',    
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'is_completed' => 'boolean',
        'total_duration_seconds' => 'integer',
        'violation_count' => 'integer', 
    ];

    // Your existing methods...
    public function user() { return $this->belongsTo(User::class); }
    public function resume() { return $this->belongsTo(Resume::class); }
    public function assessment() { return $this->belongsTo(Assessment::class); }
    
    public function getRemainingTimeAttribute()
    {
        if ($this->is_completed) return 0;
        $elapsed = now()->diffInSeconds($this->started_at);
        return max(0, $this->total_duration_seconds - $elapsed);
    }
}
