<?php

declare(strict_types=1);

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use Src\ChessGame\Application\Commands\ResignGameCommand;
use Src\ChessGame\Application\Handlers\IGameHandler;
use Src\ChessGame\Domain\ValueObject\GameAggregateId;

final class ResignController extends Controller
{
    public function __construct(public readonly IGameHandler $gameHandler)
    {
    }

    #[OA\Post(
        path: '/v1/game/{gameId}/resign',
        description: 'Resign a game',
        summary: 'Resign the game of chess',
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
                description: 'Successfully resigned the game of chess',
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
    public function __invoke(string $gameUuid): JsonResponse
    {
        $this->gameHandler->handleResignGame(new ResignGameCommand(
            GameAggregateId::fromString($gameUuid)
        ));

        return new JsonResponse();
    }
}
