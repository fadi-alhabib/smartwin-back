<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizVote extends Model
{
    protected $fillable = [
        'quiz_game_id',
        'question_id',
        'answer_id',
        'user_id',
    ];
}
