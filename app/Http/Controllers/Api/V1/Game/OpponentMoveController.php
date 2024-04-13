<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Game;

use App\Http\Controllers\Controller;
use Domain\ChessGame\Application\Commands\OpponentMoveCommand;
use Domain\ChessGame\Application\Handlers\IGameHandler;
use Domain\ChessGame\Domain\ValueObject\GameAggregateId;
use Illuminate\Http\JsonResponse;

final class OpponentMoveController extends Controller
{
    public function __construct(private readonly IGameHandler $gameHandler)
    {
    }

    public function __invoke(string $gameUuid): JsonResponse
    {
        $chessGame = $this->gameHandler->handleOpponentMove(new OpponentMoveCommand(
            GameAggregateId::fromString($gameUuid)
        ));
        return new JsonResponse([
            'board' => $chessGame->board,
        ]);
    }
}
