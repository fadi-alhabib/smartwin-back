<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class C4Game extends Model
{
    use HasFactory;

    protected $fillable = ['room_id', 'board', 'challenger_id', 'current_turn', 'end_time', 'game_over'];
    protected $casts = [
        'board' => 'array',
    ];
    public function room()
    {
        return $this->belongsTo(Room::class);
    }
    public function challengerId()
    {
        return $this->belongsTo(User::class, 'challenger_id');
    }
    public function makeMove($column, $playerId)
    {
        $board = json_decode($this->board, true);

        if ($column < 0 || $column >= 7) {
            return false;
        }

        for ($row = 5; $row >= 0; $row--) {
            if ($board[$row][$column] === null) {
                $board[$row][$column] = $playerId;
                $this->board = json_encode($board);
                $this->current_turn = $this->current_turn === $this->host_id ? $this->challenger_id : $this->host_id;
                $this->save();
                return true;
            }
        }

        return false;
    }
}
