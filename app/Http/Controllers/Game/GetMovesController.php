<?php

declare(strict_types=1);

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use Src\ChessGame\Application\Read\IAggregateGameQueries;
use Src\ChessGame\Domain\ValueObject\GameAggregateId;

final class GetMovesController extends Controller
{
    public function __construct(
        private readonly IAggregateGameQueries $aggregateGameQueries,
    ) {
    }

    #[OA\Get(
        path: '/v1/game/{gameId}/moves',
        description: 'Get all moves for a gam',
        summary: 'Get all moves for the game of chess',
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
                description: 'Successfully retrieved all moves for the game of chess',
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
    public function __invoke(string $gameId): JsonResponse
    {
        $results = $this->aggregateGameQueries
            ->getEventsByAggregateId(GameAggregateId::fromString($gameId));

        return new JsonResponse([
            'data' => $results,
        ]);
    }
}
