<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Game;

use App\Http\Controllers\Controller;
use Domain\ChessGame\Application\Commands\ResignGameCommand;
use Domain\ChessGame\Application\Handlers\IGameHandler;
use Domain\ChessGame\Domain\ValueObject\GameAggregateId;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

final class ResignGameController extends Controller
{
    public function __construct(public readonly IGameHandler $gameHandler)
    {
    }

    public function __invoke(string $gameUuid): JsonResponse
    {
        $this->gameHandler->handleResignGame(new ResignGameCommand(
            GameAggregateId::fromString($gameUuid)
        ));

        return new JsonResponse();
    }
}
