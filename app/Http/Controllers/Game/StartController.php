<?php

declare(strict_types=1);

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use OpenApi\Attributes as OA;
use Src\ChessGame\Application\Commands\StartGameCommand;
use Src\ChessGame\Application\Handlers\IGameHandler;
use Src\ChessGame\Domain\ValueObject\GameAggregateId;

final class StartController extends Controller
{
    public function __construct(public readonly IGameHandler $gameHandler)
    {
    }

    #[OA\Post(
        path: '/v1/game/{gameId}/start',
        description: 'Start a game',
        summary: 'Start a game of chess',
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
                description: 'Successfully started the game of chess',
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
    public function __invoke(Request $request): JsonResponse
    {
        $chessGame = $this->gameHandler->handleStartGame(new StartGameCommand(
            $gameAggregateId = GameAggregateId::fromString(Str::uuid()->toString()),
        ));

        return new JsonResponse([
            'game_uuid' => $gameAggregateId->value,
            'board' => $chessGame->board
        ]);
    }
}
