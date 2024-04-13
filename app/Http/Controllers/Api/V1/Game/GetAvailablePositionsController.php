<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Game;

use App\Http\Controllers\Controller;
use Domain\ChessGame\Application\Commands\GetAvailablePositionsCommand;
use Domain\ChessGame\Application\Handlers\IGameHandler;
use Domain\ChessGame\Application\Read\IAggregateGameQueries;
use Domain\ChessGame\Domain\ValueObject\GameAggregateId;
use Domain\ChessGame\Domain\ValueObject\Position;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class GetAvailablePositionsController extends Controller
{
    public function __construct(private readonly IGameHandler $gameHandler)
    {
    }

    public function __invoke(Request $request, string $gameUuid): JsonResponse
    {
        $validated = $request->validate([
            'position' => 'required',
        ]);

        $positions = $this->gameHandler->handleGetAvailablePositions(new GetAvailablePositionsCommand(
            GameAggregateId::fromString($gameUuid),
            Position::fromMove($validated['position']),
        ));

        return new JsonResponse([
            'positions' => $positions
        ]);
    }
}
