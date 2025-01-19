<?php

namespace App\Http\Controllers\Api\Room\Quiz;

use App\Events\Room\NoTimeRemaining;
use App\Events\Room\Quiz\QuizGameAnswerMade;
use App\Events\Room\Quiz\QuizGameOver;
use App\Events\Room\Quiz\QuizGameStarted;
use App\Http\Controllers\Controller;
use App\Http\Resources\Room\Quiz\QuestionResource;
use App\Models\Answer;
use App\Models\Question;
use App\Models\QuizGames;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('api/room/{room}/quiz')]
class QuizGameController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    #[Get('/start')]
    public function start(Room $room, Request $request)
    {
        if ($room->available_time === 0) {
            return $this->failed('No time left', 403);
        }
        $isImagesGame = $request->input('is_images_game');

        $game = new QuizGames();
        $game->images_game = $isImagesGame;
        $game->room_id = $room->id;

        $question = Question::where('status', true)->with('answers')
            ->inRandomOrder()->first();

        broadcast(new QuizGameStarted($game, $question));

        return $this->success(data: new QuestionResource($question), message: "لقد بدأت لعبة الاسئلة");
    }

    #[Get('/{quiz}/answer/{answer}')]
    public function answer(
        Room $room,
        QuizGames $quiz,
        Answer $answer,
        Request $request,
    ) {
        $rightAnswer = false;
        if ($quiz->game_over) {
            return $this->failed(
                message: 'This game is not valid anymore',
                statusCode: 403,
            );
        }



        if ($quiz->questions_count === 10) {
            $user = $request->user();
            $quiz->game_over = true;
            if ($user->points < 500) {
                $score = $quiz->right_answers_count > 8 ? 10 : 5;
                $user->points += $score;
            } else {
                $score = $quiz->right_answers_count > 8 ? 10 : -10;
                $user->points +=  $score;
            }

            $quiz->end_time =  now();
            $created_at = Carbon::parse($quiz->created_at);
            $minutes_taken = (int) $created_at->diffInMinutes($quiz->end_time);
            $room->available_time -= $minutes_taken;
            if ($room->available_time <= 0) {
                $room->available_time = 0;
                broadcast(new NoTimeRemaining());
            }

            broadcast(new QuizGameOver(score: $score, game: $quiz));
            $quiz->save();
            $room->save();
            $user->save();
            return $this->success();
        }
        if ($answer & $answer->is_correct) {
            $rightAnswer = true;
            $quiz->right_answers_count += 1;
        }
        if (!$quiz->images_game) {
            $question = Question::where('status', true)
                ->with('answers')
                ->inRandomOrder()
                ->first();
        } else {
            $question = Question::where('status', true)
                ->whereNotNull('image')
                ->with('answers')
                ->inRandomOrder()->first();
        }

        $quiz->questions_count += 1;
        $quiz->save();
        $room->save();

        broadcast(new QuizGameAnswerMade(
            room_id: $room->id,
            rightAnswer: $rightAnswer,
            question: $question,
        ));

        return $this->success('question answered');
    }
}
