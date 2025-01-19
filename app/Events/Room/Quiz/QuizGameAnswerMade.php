<?php

namespace App\Events\Room\Quiz;

use App\Http\Resources\Room\Quiz\QuestionResource;
use App\Models\Question;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class QuizGameAnswerMade
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        private readonly int $room_id,
        private readonly bool $rightAnswer,
        private readonly Question $question,
    ) {}

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn()
    {
        return ['room-' . $this->room_id];
    }

    public function broadcastAs()
    {
        return 'quiz-game-question';
    }

    public function broadcastWith()
    {
        return [
            'rightAnswer' => $this->rightAnswer,
            'question' => new QuestionResource($this->question)
        ];
    }
}
