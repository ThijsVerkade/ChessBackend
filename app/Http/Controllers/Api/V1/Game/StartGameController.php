<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Game;

use App\Http\Controllers\Controller;
use Domain\ChessGame\Application\Commands\StartGameCommand;
use Domain\ChessGame\Application\Handlers\IGameHandler;
use Domain\ChessGame\Domain\ValueObject\GameAggregateId;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

final class StartGameController extends Controller
{
    public function __construct(public readonly IGameHandler $gameHandler)
    {
    }


    public function __invoke(Request $request): JsonResponse
    {
        $board = $this->gameHandler->handleStartGame(new StartGameCommand(
            $gameAggregateId = GameAggregateId::fromString(Str::uuid()->toString()),
        ));

        return new JsonResponse([
            'game_uuid' => $gameAggregateId->value,
            'board' => $board->board
        ]);
    }
}
