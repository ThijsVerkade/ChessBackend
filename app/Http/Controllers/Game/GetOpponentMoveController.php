<?php

declare(strict_types=1);

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use Src\ChessGame\Application\Commands\OpponentMoveCommand;
use Src\ChessGame\Application\Handlers\IGameHandler;
use Src\ChessGame\Domain\ValueObject\GameAggregateId;

final class GetOpponentMoveController extends Controller
{
    public function __construct(private readonly IGameHandler $gameHandler)
    {
    }

    #[OA\Get(
        path: '/v1/game/{gameId}/opponent-move',
        description: 'Get the opponent move for a game',
        summary: 'Get the opponent move for the game of chess',
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
                description: 'Successfully retrieved the opponent move for the game of chess',
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
        $chessGame = $this->gameHandler->handleOpponentMove(new OpponentMoveCommand(
            GameAggregateId::fromString($gameUuid)
        ));
        return new JsonResponse([
            'board' => $chessGame->board,
        ]);
    }
}
