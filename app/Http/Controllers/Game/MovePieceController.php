<?php

declare(strict_types=1);

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;
use Src\ChessGame\Application\Commands\MovePieceCommand;
use Src\ChessGame\Application\Handlers\IGameHandler;
use Src\ChessGame\Domain\ValueObject\GameAggregateId;
use Src\ChessGame\Domain\ValueObject\Position;

final class MovePieceController extends Controller
{
    public function __construct(private readonly IGameHandler $gameHandler)
    {
    }

    #[OA\Post(
        path: '/v1/game/{gameId}/move-piece',
        description: 'Move a piece in a game',
        summary: 'Move a piece in the game of chess',
        requestBody: new OA\RequestBody(
            description: 'Request body parameters',
            required: true,
            content: [
                'application/json' => new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(
                        properties: [
                            new OA\Property(
                                property: 'start_move',
                                description: 'Start move',
                                type: 'string',
                            ),
                            new OA\Property(
                                property: 'end_move',
                                description: 'End move',
                                type: 'string',
                            ),
                        ],
                        type: 'object'
                    )
                ),
            ]
        ),
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
                description: 'Successfully moved the piece in the game of chess',
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
            'start_move' => 'required',
            'end_move' => 'required',
        ]);

        $chessGame = $this->gameHandler->handleMovePiece(new MovePieceCommand(
            GameAggregateId::fromString($gameId),
            Position::fromMove($validated['start_move']),
            Position::fromMove($validated['end_move']),
        ));

        return new JsonResponse([
            'board' => $chessGame->board,
        ]);
    }
}
