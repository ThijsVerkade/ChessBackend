<?php

declare(strict_types=1);

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;

final class DrawController extends Controller
{
    #[OA\Post(
        path: '/v1/game/{gameId}/draw',
        description: 'Draw a game',
        summary: 'Draw the game of chess',
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
                response: 204,
                description: 'Successfully drew the game of chess',
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
        // TODO: Implement __invoke() method.

        return new JsonResponse(status: Response::HTTP_NO_CONTENT);
    }
}
