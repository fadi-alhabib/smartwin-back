<?php

namespace App\Http\Controllers\Api\Room\Quiz;

use App\Events\Room\Quiz\QuizGameStarted;
use App\Http\Controllers\Controller;
use App\Http\Resources\Room\Quiz\ImageQuestionResource;
use App\Http\Resources\Room\Quiz\QuestionResource;
use App\Models\Answer;
use App\Models\Question;
use App\Models\QuizGames;
use App\Models\QuizVote;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            'game_id'   => $game->id,
            'room_id'   => $room->id,
            'questions' => $questions,
        ]);

        return $this->success(data: [
            "game_id"   => $game->id,
            "questions" => $questions,
        ], message: "لقد بدأت لعبة الاسئلة");
    }

    #[Post(uri: '/{quiz}/end', middleware: ['auth:sanctum'])]
    public function end(Room $room, QuizGames $quiz, Request $request)
    {
        $user = $request->user();
        if ($room->host_id != $user->id) {
            return $this->failed('Not your game', 403);
        }

        if ($quiz->game_over) {
            return $this->failed('This game is not valid anymore', 403);
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

        $score = $this->calculateScore($quiz->right_answers_count);
        $user->points += $score;

        $room->available_time -= $minutes_taken;
        if ($room->available_time <= 0) {
            $room->available_time = 0;
            $room->consumed_at = now();
            $this->pusher->trigger('room.' . $room->id, 'no.time', []);
        }

        $this->pusher->trigger('room.' . $room->id, 'quiz.over', [
            'score'         => $score,
            'minutes_taken' => $minutes_taken,
        ]);

        $quiz->save();
        $room->save();
        $user->save();

        return $this->success(data: [
            'score'         => $score,
            'minutes_taken' => $minutes_taken,
        ], message: "Game over");
    }



    #[Post(uri: 'broadcast-answer', middleware: ['auth:sanctum'])]
    public function broadcastAnswer(Room $room, Request $request)
    {
        $user = $request->user();
        if ($room->host_id != $user->id) {
            return $this->failed('Not your game', 403);
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
            'answerId'  => $answerId,
            'isCorrect' => $answer->is_correct,
        ]);

        return $this->success(message: "Answer broadcasted");
    }

    /**
     * NEW: Spectator endpoint to vote for an answer on a specific question.
     * Each spectator (non-host) can vote once per question per quiz game.
     * After saving the vote, the updated vote counts for that question are broadcasted realtime.
     */
    #[Post(uri: '/{quiz}/question/{question}/vote', middleware: ['auth:sanctum'])]
    public function voteForAnswer(Room $room, QuizGames $quiz, Question $question, Request $request)
    {
        $user = $request->user();


        if ($room->host_id == $user->id) {
            return $this->failed('Host cannot vote in his own game.', 403);
        }

        $answerId = $request->input('answer_id');
        if (!$answerId) {
            return $this->failed('answer_id is required', 400);
        }


        $answer = Answer::where('id', $answerId)
            ->where('question_id', $question->id)
            ->first();
        if (!$answer) {
            return $this->failed('Invalid answer for this question.', 404);
        }


        $existingVote = QuizVote::where('quiz_game_id', $quiz->id)
            ->where('question_id', $question->id)
            ->where('user_id', $user->id)
            ->first();
        if ($existingVote) {
            return $this->failed('You have already voted for this question.', 400);
        }


        QuizVote::create([
            'quiz_game_id' => $quiz->id,
            'question_id'  => $question->id,
            'answer_id'    => $answerId,
            'user_id'      => $user->id,
        ]);


        $votes = QuizVote::where('quiz_game_id', $quiz->id)
            ->where('question_id', $question->id)
            ->select('answer_id', DB::raw('COUNT(*) as vote_count'))
            ->groupBy('answer_id')
            ->get()
            ->keyBy('answer_id')
            ->map(function ($item) {
                return $item->vote_count;
            });


        $this->pusher->trigger('room.' . $room->id, 'quiz.vote.updated', [
            'question_id' => $question->id,
            'votes'       => $votes,
        ]);

        return $this->success(message: 'Your vote has been recorded.');
    }


    #[Get(uri: '/{quiz}/votes', middleware: ['auth:sanctum'])]
    public function getVotes(Room $room, QuizGames $quiz, Request $request)
    {
        $user = $request->user();


        if ($room->host_id != $user->id) {
            return $this->failed('Only the host can access the vote counts.', 403);
        }

        if ($quiz->did_reveal_votes) {
            return $this->failed('You only have one chance of revealing votes');
        }
        $aggregatedVotes = QuizVote::where('quiz_game_id', $quiz->id)
            ->select('question_id', 'answer_id', DB::raw('COUNT(*) as vote_count'))
            ->groupBy('question_id', 'answer_id')
            ->get();


        $result = [];
        foreach ($aggregatedVotes as $vote) {
            $result[$vote->question_id][$vote->answer_id] = $vote->vote_count;
        }


        $quiz->did_reveal_votes = true;
        $quiz->save();

        return $this->success(
            data: ['votes' => $result],
            message: 'Vote counts retrieved successfully.'
        );
    }


    private function fetchQuestions($isImagesGame, $count)
    {
        if ($isImagesGame) {
            return ImageQuestionResource::collection(
                Question::where('status', true)
                    ->whereNotNull('image')
                    ->whereHas('answers')
                    ->with('answers')
                    ->inRandomOrder()
                    ->limit($count)
                    ->get()
            );
        } else {
            return QuestionResource::collection(
                Question::where('status', true)
                    ->whereNull('image')
                    ->whereHas('answers')
                    ->with('answers')
                    ->inRandomOrder()
                    ->limit($count)
                    ->get()
            );
        }
    }

    private function calculateScore($rightAnswersCount)
    {

        return $rightAnswersCount > 8 ? 10 : ($rightAnswersCount > 6 ? 5 : -10);
    }
}
