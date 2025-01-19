<?php

namespace App\Http\Controllers\Api\Room\C4;

use App\Events\Room\C4\GameDraw;
use App\Events\Room\C4\C4GameStarted;
use App\Events\Room\C4\GameWon;
use App\Events\Room\C4\MoveMade;
use App\Http\Controllers\Controller;
use App\Models\C4Game;
use App\Models\Room;
use Illuminate\Http\Request;


class C4GameController extends Controller
{
    public function start(Room $room, Request $request)
    {
        $challengerId = $request->challenger_id;

        $game = C4Game::create([
            'room_id' => $room->id,
            'host_id' => $room->host_id,
            'challenger_id' => $challengerId,
            'current_turn' => $room->host_id,
            'board' => json_encode(array_fill(0, 6, array_fill(0, 7, null)))
        ]);

        broadcast(new C4GameStarted($game));

        return $this->success(data: $game);
    }

    public function makeMove(C4Game $game, Request $request)
    {
        $column = $request->column;
        $playerId = $request->user()->id;

        if ($playerId !== $game->current_turn) {
            return response()->json(['error' => 'Not your turn'], 403);
        }

        $success = $game->makeMove($column, $playerId);

        if (!$success) {
            return response()->json(['error' => 'Invalid move'], 400);
        }

        broadcast(new MoveMade($game));

        if ($this->checkWin($game)) {
            broadcast(new GameWon($game, $playerId));
            return response()->json(['message' => 'Game won']);
        }

        if ($this->checkDraw($game)) {
            broadcast(new GameDraw($game));
            return response()->json(['message' => 'Game draw']);
        }

        return response()->json($game);
    }



    public function state(C4Game $game)
    {
        return $this->success(data: $game);
    }

    private function checkWin(C4Game $game)
    {
        $board = json_decode($game->board, true);

        for ($row = 0; $row < 6; $row++) {
            for ($col = 0; $col < 7; $col++) {
                if ($this->isWinningPosition($board, $row, $col)) {
                    return true;
                }
            }
        }

        return false;
    }

    private function isWinningPosition($board, $row, $col)
    {
        if ($board[$row][$col] === null) {
            return false;
        }

        $player = $board[$row][$col];

        return $this->checkDirection($board, $row, $col, 0, 1, $player) || // Horizontal
            $this->checkDirection($board, $row, $col, 1, 0, $player) || // Vertical
            $this->checkDirection($board, $row, $col, 1, 1, $player) || // Diagonal (down-right)
            $this->checkDirection($board, $row, $col, 1, -1, $player);  // Diagonal (down-left)
    }

    private function checkDirection($board, $row, $col, $dRow, $dCol, $player)
    {
        $count = 0;

        for ($i = 0; $i < 4; $i++) {
            $r = $row + $i * $dRow;
            $c = $col + $i * $dCol;

            if ($r < 0 || $r >= 6 || $c < 0 || $c >= 7 || $board[$r][$c] !== $player) {
                return false;
            }

            $count++;
        }

        return $count === 4;
    }

    private function checkDraw(C4Game $game)
    {
        $board = json_decode($game->board, true);

        foreach ($board as $row) {
            if (in_array(null, $row, true)) {
                return false;
            }
        }

        return true;
    }
}
