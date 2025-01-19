<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizGames extends Model
{
    /** @use HasFactory<\Database\Factories\QuizGamesFactory> */
    use HasFactory;

    protected $fillable = [
        'room_id',
        'questions_count',
        'right_answers_count',
        'time_consumed',
        'images_game',
        'game_over',
        "end_time",
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
