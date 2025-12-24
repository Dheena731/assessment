<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resume extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'file_path',
        'parsed_text',
        'is_valid',
        'skill_score',        
        'threshold_score',     
        'matched_skills',     
    ];

    protected $casts = [
        'matched_skills' => 'array',  
        'is_valid' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
