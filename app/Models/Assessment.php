<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    protected $fillable = [
        'name', 'description', 'threshold_score', 
        'duration_minutes', 'pass_percentage', 'is_active'
    ];

    public function userAssessments()
    {
        return $this->hasMany(UserAssessment::class);
    }
}
