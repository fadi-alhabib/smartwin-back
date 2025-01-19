<?php

namespace App\Events\Room\Quiz;

use App\Http\Resources\Room\Quiz\ImageQuestionResource;
use App\Http\Resources\Room\Quiz\QuestionResource;
use App\Models\Question;
use App\Models\QuizGames;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class QuizGameStarted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        private readonly QuizGames $game,
        private readonly Question $question,
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
        return 'quiz-game-started';
    }

    public function broadcastWith()
    {
        if ($this->game->images_game) {
            return new ImageQuestionResource($this->question);
        } else {
            return new QuestionResource($this->question);
        }
    }
}
