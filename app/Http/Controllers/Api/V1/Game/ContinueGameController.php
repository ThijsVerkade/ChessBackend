<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Game;

use App\Http\Controllers\Controller;
use Domain\ChessGame\Application\Commands\ContinueGameCommand;
use Domain\ChessGame\Application\Handlers\IGameHandler;
use Domain\ChessGame\Domain\ValueObject\GameAggregateId;
use Illuminate\Http\JsonResponse;

final class ContinueGameController extends Controller
{
    public function __construct(public readonly IGameHandler $gameHandler)
    {
    }

    public function __invoke(string $uuid): JsonResponse
    {
        $board = $this->gameHandler->handleContinueGame(new ContinueGameCommand(
            $gameAggregateId = GameAggregateId::fromString($uuid),
        ));

        return new JsonResponse([
            'game_uuid' => $gameAggregateId->value,
            'board' => $board->board
        ]);
    }
}
