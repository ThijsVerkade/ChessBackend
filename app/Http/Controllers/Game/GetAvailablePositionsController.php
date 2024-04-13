<?php

declare(strict_types=1);

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;
use Src\ChessGame\Application\Commands\GetAvailablePositionsCommand;
use Src\ChessGame\Application\Handlers\IGameHandler;
use Src\ChessGame\Domain\ValueObject\GameAggregateId;
use Src\ChessGame\Domain\ValueObject\Position;

final class GetAvailablePositionsController extends Controller
{
    public function __construct(private readonly IGameHandler $gameHandler)
    {
    }

    #[OA\Get(
        path: '/v1/game/{gameId}/available-positions',
        description: 'Get all available positions for a game',
        summary: 'Get all available positions for the game of chess',
        tags: ['Game'],
        parameters: [
            new OA\Parameter(
                name: 'gameId',
                description: 'The UUID of the game',
                in: 'path',
                required: true,
                schema: new OA\Schema(
                    type: 'string',
                    format: 'uuid'
                )
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successfully retrieved all available positions for the game of chess',
            ),
            new OA\Response(
                response: 404,
                description: 'The game was not found'
            ),
            new OA\Response(
                response: 500,
                description: 'An error occurred'
            )
        ]
    )]
    public function __invoke(Request $request, string $gameId): JsonResponse
    {
        $validated = $request->validate([
            'position' => 'required',
        ]);

        $positions = $this->gameHandler->handleGetAvailablePositions(new GetAvailablePositionsCommand(
            GameAggregateId::fromString($gameId),
            Position::fromMove($validated['position']),
        ));

        return new JsonResponse([
            'positions' => $positions
        ]);
    }
}
