<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Game;

use App\Http\Controllers\Controller;
use Domain\ChessGame\Application\Commands\MovePieceCommand;
use Domain\ChessGame\Application\Handlers\IGameHandler;
use Domain\ChessGame\Domain\ValueObject\GameAggregateId;
use Domain\ChessGame\Domain\ValueObject\Position;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class MovePieceController extends Controller
{
    public function __construct(private readonly IGameHandler $gameHandler)
    {
    }

    public function __invoke(Request $request, string $gameUuid): JsonResponse
    {
        $validated = $request->validate([
            'start_move' => 'required',
            'end_move' => 'required',
        ]);

        $chessGame = $this->gameHandler->handleMovePiece(new MovePieceCommand(
            GameAggregateId::fromString($gameUuid),
            Position::fromMove($validated['start_move']),
            Position::fromMove($validated['end_move']),
        ));

        return new JsonResponse([
            'board' => $chessGame->board,
        ]);
    }
}
