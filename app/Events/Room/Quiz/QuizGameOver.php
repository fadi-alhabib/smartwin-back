<?php

namespace App\Events\Room\Quiz;

use App\Models\QuizGames;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class QuizGameOver
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        private readonly int $score,
        private readonly QuizGames $game,
    ) {}

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn()
    {
        return ['room-' . $this->game->room_id];
    }

    public function broadcastAs()
    {
        return 'quiz-game-over';
    }

    public function broadcastWith()
    {
        return [
            'score' => $this->score
        ];
    }
}
