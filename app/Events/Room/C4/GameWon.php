<?php

namespace App\Events\Room\C4;

use App\Models\C4Game;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GameWon implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $game;
    public $winner;

    public function __construct(C4Game $game, $winner)
    {
        $this->game = $game;
        $this->winner = $winner;
    }

    public function broadcastOn()
    {
        return new Channel('room-' . $this->game->room_id);
    }
}
