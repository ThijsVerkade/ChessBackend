<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Game;

use App\Http\Controllers\Controller;
use Domain\ChessGame\Application\Commands\MovePieceCommand;
use Domain\ChessGame\Application\Commands\PromotePawnCommand;
use Domain\ChessGame\Application\Handlers\IGameHandler;
use Domain\ChessGame\Domain\Enum\PieceType;
use Domain\ChessGame\Domain\ValueObject\GameAggregateId;
use Domain\ChessGame\Domain\ValueObject\Position;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class PromotePawnController extends Controller
{
    public function __construct(private readonly IGameHandler $gameHandler)
    {
    }

    public function __invoke(Request $request, string $gameUuid): JsonResponse
    {
        $validated = $request->validate([
            'position' => 'required',
            'pieceType' => 'required',
        ]);

        $chessGame = $this->gameHandler->handlePromotePawn(new PromotePawnCommand(
            GameAggregateId::fromString($gameUuid),
            Position::fromMove($validated['position']),
            PieceType::from($validated['pieceType'])
        ));

        return new JsonResponse([
            'board' => $chessGame->board
        ]);
    }
}
