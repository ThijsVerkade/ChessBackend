<?php

declare(strict_types=1);

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use Src\ChessGame\Application\Commands\ContinueGameCommand;
use Src\ChessGame\Application\Handlers\IGameHandler;
use Src\ChessGame\Domain\ValueObject\GameAggregateId;

final class ContinueController extends Controller
{
    public function __construct(public readonly IGameHandler $gameHandler)
    {
    }

    #[OA\Post(
        path: '/v1/game/{gameId}/continue',
        description: 'Continue a game',
        summary: 'Continue the game of chess',
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
                description: 'Successfully continued the game of chess',
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
        $chessGame = $this->gameHandler->handleContinueGame(new ContinueGameCommand(
            $gameAggregateId = GameAggregateId::fromString($gameId),
        ));

        return new JsonResponse([
            'game_uuid' => $gameAggregateId->value,
            'board' => $chessGame->board
        ]);
    }
}
