<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAssessment extends Model
{
    protected $fillable = [
        'user_id', 'resume_id', 'assessment_id',
        'selected_questions', 'user_answers', 'score',
        'is_completed', 'started_at', 'ended_at'
    ];

    protected $casts = [
        'selected_questions' => 'array',
        'user_answers' => 'array',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function resume()
    {
        return $this->belongsTo(Resume::class);
    }

    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }
}
