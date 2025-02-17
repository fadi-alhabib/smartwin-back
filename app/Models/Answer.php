<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;
    protected $fillable = ['question_id', 'title', 'is_correct'];
    protected $hidden = ['is_correct'];
    // Relationships
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
