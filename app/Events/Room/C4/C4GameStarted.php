<?php

namespace App\Events\Room\C4;

use App\Models\C4Game;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class C4GameStarted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $game;

    public function __construct(C4Game $game)
    {
        $this->game = $game;
    }

    public function broadcastOn()
    {
        return ['room-' . $this->game->room_id];
    }
    public function broadcastAs()
    {
        return 'c4-game-started';
    }
}
