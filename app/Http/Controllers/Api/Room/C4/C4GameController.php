<?php

namespace App\Http\Controllers\Api\Room\C4;

use App\Models\C4Game;
use App\Models\Room;
// We'll create this event later
use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Pusher\Pusher;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('api/room/{room}/c4'), Middleware(['auth:sanctum'])]
class C4GameController extends Controller
{
    public function __construct(private readonly Pusher $pusher) {}

    #[Post('/start')]
    public function startGame(Request $request, Room $room)
    {
        $userId = $request->user()->id;

        if ($room->host_id !== $userId) {
            return $this->failed('Only the room host can start the game.', 403);
        }

        $game = C4Game::create([
            'room_id' => $room->id,
            'board' => $this->initializeBoard(),
            'challenger_id' => $request->challenged_id,
            'current_turn' => $userId, // Host starts first
        ]);
        // return $this->success(["d" => $game], statusCode: 400);
        $this->pusher->trigger('room.' . $room->id, 'c4.started', [
            'game_id' => $game->id,
            'challenger_id' => $game->challenger_id,
            'current_turn' => $userId,
        ]);
        return $this->success(['message' => 'Game started.', 'game_id' => $game->id, 'current_turn' => $userId, 'challenger_id' => $game->challenger_id,]);
    }

    #[Post('/{gameId}/make-move')]
    public function makeMove(Request $request, Room $room, int $gameId)
    {
        $userId = $request->user()->id;
        $request->validate([
            'column' => 'required|integer|min:0|max:6',
        ]);
        $c4game = C4Game::find($gameId);

        if ($c4game->current_turn !== $userId) {
            return $this->failed('Not your turn.', 400);
        }
        $board = $c4game->board;
        $column = $request->column;

        $row = $this->getNextAvailableRow($board, $column);
        if ($row === -1) {
            return $this->failed('Invalid move. Column is full.', 400);
        }

        $player = ($c4game->current_turn == $c4game->room->host_id) ? 1 : 2; // 1 for host, 2 for challenger
        $board[$row][$column] = $player;
        $c4game->board = $board;

        // TODO:: CALCULATE POINTS ON WIN
        if ($this->checkWin($board, $player)) {
            $message = 'Player ' . $player . ' wins!';
            $winner = User::find($c4game->current_turn);
            $winner->points += 10;
            $winner->save();
            $c4game->game_over = true;
            $c4game->end_time = now();
            $created_at = Carbon::parse($c4game->created_at);
            $minutes_taken = (int) $created_at->diffInMinutes($c4game->end_time);
            $room->available_time -= $minutes_taken;
            if ($room->available_time <= 0) {
                $room->available_time = 0;
                $this->pusher->trigger('room.' . $room->id, 'no.time', []);
            }
            $room->save();
        } elseif ($this->checkDraw($board)) {
            $message = 'c4game draw!';
            $c4game->game_over = true;
            $c4game->end_time = now();
            $created_at = Carbon::parse($c4game->created_at);
            $minutes_taken = (int) $created_at->diffInMinutes($c4game->end_time);
            $room->available_time -= $minutes_taken;
            if ($room->available_time <= 0) {
                $room->available_time = 0;
                $this->pusher->trigger('room.' . $room->id, 'no.time', []);
            }
            $room->save();
        } else {
            $c4game->current_turn = ($c4game->current_turn == $c4game->room->host_id) ? $c4game->challenger_id : $c4game->room->host_id;
            $message = 'Move made. Next turn.';
        }

        $c4game->save();
        $this->broadcastGameUpdate($c4game, 'move.made', $message);

        return response()->json(['message' => $message, 'game_state' => $c4game, 'current_turn' => $c4game->current_turn]);
    }

    public function getGame(C4Game $game)
    {
        return response()->json($game);
    }

    private function initializeBoard()
    {
        return array_fill(0, 6, array_fill(0, 7, 0)); // 6 rows, 7 columns, 0 for empty
    }

    private function getNextAvailableRow($board, $column)
    {
        for ($row = 5; $row >= 0; $row--) {
            if ($board[$row][$column] == 0) {
                return $row;
            }
        }
        return -1; // Column is full
    }

    private function checkWin($board, $player)
    {
        // Check horizontal
        for ($row = 0; $row < 6; $row++) {
            for ($col = 0; $col < 4; $col++) {
                if (
                    $board[$row][$col] == $player &&
                    $board[$row][$col + 1] == $player &&
                    $board[$row][$col + 2] == $player &&
                    $board[$row][$col + 3] == $player
                ) {
                    return true;
                }
            }
        }
        // Check vertical
        for ($row = 0; $row < 3; $row++) {
            for ($col = 0; $col < 7; $col++) {
                if (
                    $board[$row][$col] == $player &&
                    $board[$row + 1][$col] == $player &&
                    $board[$row + 2][$col] == $player &&
                    $board[$row + 3][$col] == $player
                ) {
                    return true;
                }
            }
        }
        // Check diagonals (top-left to bottom-right)
        for ($row = 0; $row < 3; $row++) {
            for ($col = 0; $col < 4; $col++) {
                if (
                    $board[$row][$col] == $player &&
                    $board[$row + 1][$col + 1] == $player &&
                    $board[$row + 2][$col + 2] == $player &&
                    $board[$row + 3][$col + 3] == $player
                ) {
                    return true;
                }
            }
        }
        // Check diagonals (bottom-left to top-right)
        for ($row = 3; $row < 6; $row++) {
            for ($col = 0; $col < 4; $col++) {
                if (
                    $board[$row][$col] == $player &&
                    $board[$row - 1][$col + 1] == $player &&
                    $board[$row - 2][$col + 2] == $player &&
                    $board[$row - 3][$col + 3] == $player
                ) {
                    return true;
                }
            }
        }
        return false;
    }

    private function checkDraw($board)
    {
        for ($row = 0; $row < 6; $row++) {
            for ($col = 0; $col < 7; $col++) {
                if ($board[$row][$col] == 0) {
                    return false; // Found empty cell, not a draw
                }
            }
        }
        return true; // Board is full, it's a draw
    }


    private function broadcastGameUpdate(C4Game $game, $event, $message = null)
    {

        $data = [
            'game_id' => $game->id,
            'board' => $game->board,
            'current_turn' => $game->current_turn,
            'message' => $message,
        ];

        $this->pusher->trigger('room.' . $game->room->id, $event, $data);
    }
}
