<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'question', 'type', 'hint', 'skill', 'expected_format', 'ai_rubric'
    ];

    public function options()
    {
        return $this->hasMany(Option::class);
    }
}
