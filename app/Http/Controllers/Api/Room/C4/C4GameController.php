<?php

namespace App\Http\Controllers;

use App\Models\C4Game;
use App\Models\Room;
use App\Events\GameUpdated; // We'll create this event later
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Pusher\Pusher;

class GameController extends Controller
{
    public function __construct(private readonly Pusher $pusher) {}
    public function startGame(Request $request, Room $room)
    {

        if ($room->host_id !== Auth::id()) {
            return $this->failed('Only the room host can start the game.', 403);
        }

        $game = C4Game::create([
            'room_id' => $room->id,
            'board' => $this->initializeBoard(),
            'challenger_id' => $request->challenged_id,
            'current_turn' => $room->host_id, // Host starts first
        ]);

        $this->pusher->trigger('room.' . $room->id, 'c4.started', [
            'game_id' => $game->id,
            'challenger_id' => $game->challenger_id,
            'current_turn' => $room->current_turn,
        ]);
        return $this->success(['message' => 'Game started.', 'game_id' => $game->id, 'current_turn' => $game->current_turn]);
    }

    public function makeMove(Request $request, C4Game $game)
    {
        $request->validate([
            'column' => 'required|integer|min:0|max:6',
        ]);
        if ($game->current_turn !== Auth::id()) {
            return $this->failed('Not your turn.', 400);
        }
        $board = $game->board;
        $column = $request->column;

        $row = $this->getNextAvailableRow($board, $column);
        if ($row === -1) {
            return $this->failed('Invalid move. Column is full.', 400);
        }

        $player = ($game->current_turn == $game->room->host_id) ? 1 : 2; // 1 for host, 2 for challenger
        $board[$row][$column] = $player;
        $game->board = $board;

        if ($this->checkWin($board, $player)) {
            $message = 'Player ' . $player . ' wins!';
            $game->current_turn = null; // Game over
        } elseif ($this->checkDraw($board)) {
            $message = 'Game draw!';
            $game->current_turn = null; // Game over
        } else {
            $game->current_turn = ($game->current_turn == $game->room->host_id) ? $game->challenger_id : $game->room->host_id;
            $message = 'Move made. Next turn.';
        }

        $game->save();
        $this->broadcastGameUpdate($game, 'move.made', $message);

        return response()->json(['message' => $message, 'game_state' => $game, 'current_turn' => $game->current_turn]);
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
