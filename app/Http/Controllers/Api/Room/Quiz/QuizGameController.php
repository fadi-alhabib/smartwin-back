<?php

namespace App\Http\Controllers\Api\Room\Quiz;

use App\Events\Room\NoTimeRemaining;
use App\Events\Room\Quiz\QuizGameAnswerMade;
use App\Events\Room\Quiz\QuizGameOver;
use App\Events\Room\Quiz\QuizGameStarted;
use App\Http\Controllers\Controller;
use App\Http\Resources\Room\Quiz\ImageQuestionResource;
use App\Http\Resources\Room\Quiz\QuestionResource;
use App\Models\Answer;
use App\Models\Question;
use App\Models\QuizGames;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Pusher\Pusher;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('api/room/{room}/quiz')]
class QuizGameController extends Controller
{
    public function __construct(private readonly Pusher $pusher) {}

    #[Get(uri: '/start', middleware: ['auth:sanctum'])]
    public function start(Room $room, Request $request)
    {
        if ($room->available_time === 0) {
            return $this->failed('No time left', 403);
        }

        $isImagesGame = $request->input('is_images_game') === 'true';

        $game = new QuizGames();
        $game->images_game = $isImagesGame;
        $game->room_id = $room->id;
        $game->questions_count = 10;
        $game->save();

        $questions = $this->fetchQuestions($isImagesGame, 10);

        $this->pusher->trigger('room.' . $room->id, 'quiz.started', [
            'game_id' => $game->id,
            'room_id' => $room->id,
            'questions' => $questions,
        ]);

        return $this->success(data: [
            "game_id" => $game->id,
            "questions" => $questions,
        ], message: "لقد بدأت لعبة الاسئلة");
    }

    #[Post(uri: '/{quiz}/end', middleware: ['auth:sanctum'])]
    public function end(Room $room, QuizGames $quiz, Request $request)
    {
        $user = $request->user();
        if ($room->host_id != $user->id) {
            return $this->failed('not your game', 403);
        }

        if ($quiz->game_over) {
            return $this->failed(
                message: 'This game is not valid anymore',
                statusCode: 403,
            );
        }

        $rightQuestionsCount = $request->input('rightQuestionsCount');
        if (!is_numeric($rightQuestionsCount) || $rightQuestionsCount < 0 || $rightQuestionsCount > 10) {
            return $this->failed('Invalid rightQuestionsCount', 400);
        }

        $quiz->game_over = true;
        $quiz->end_time = now();
        $quiz->right_answers_count = $rightQuestionsCount;

        $created_at = Carbon::parse($quiz->created_at);
        $minutes_taken = (int) $created_at->diffInMinutes($quiz->end_time);

        $score = $this->calculateScore($quiz->right_answers_count, $user->points);
        $user->points += $score;

        $room->available_time -= $minutes_taken;
        if ($room->available_time <= 0) {
            $room->available_time = 0;
            $this->pusher->trigger('room.' . $room->id, 'no.time', []);
        }

        $this->pusher->trigger('room.' . $room->id, 'quiz.over', [
            'score' => $score,
            'minutes_taken' => $minutes_taken,
        ]);

        $quiz->save();
        $room->save();
        $user->save();

        return $this->success(data: [
            'score' => $score,
            'minutes_taken' => $minutes_taken,
        ], message: "Game over");
    }

    #[Post(uri: 'broadcast-answer', middleware: ['auth:sanctum'])]
    public function broadcastAnswer(
        Room $room,
        Request $request
    ) {
        $user = $request->user();
        if ($room->host_id != $user->id) {
            return $this->failed('not your game', 403);
        }

        $answerId = $request->input('answerId');
        if (!$answerId) {
            return $this->failed('answerId is required', 400);
        }

        $answer = Answer::find($answerId);
        if (!$answer) {
            return $this->failed('Answer not found', 404);
        }

        $this->pusher->trigger('room.' . $room->id, 'answer.made', [
            'answerId' => $answerId,
            'isCorrect' => $answer->is_correct,
        ]);

        return $this->success(message: "Answer broadcasted");
    }

    private function fetchQuestions($isImagesGame, $count)
    {
        if ($isImagesGame) {
            return ImageQuestionResource::collection(
                Question::where('status', true)
                    ->whereNotNull('image')
                    ->with('answers')
                    ->inRandomOrder()
                    ->limit($count)
                    ->get()
            );
        } else {
            return QuestionResource::collection(
                Question::where('status', true)
                    ->whereNull('image')
                    ->with('answers')
                    ->inRandomOrder()
                    ->limit($count)
                    ->get()
            );
        }
    }

    private function calculateScore($rightAnswersCount, $userPoints)
    {
        if ($userPoints < 500) {
            return $rightAnswersCount > 8 ? 10 : ($rightAnswersCount > 6 ? 5 : 0);
        } else {
            return $rightAnswersCount > 8 ? 10 : -10;
        }
    }
}
