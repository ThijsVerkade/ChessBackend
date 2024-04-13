<?php

declare(strict_types=1);

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;
use Src\ChessGame\Application\Commands\PromotePawnCommand;
use Src\ChessGame\Application\Handlers\IGameHandler;
use Src\ChessGame\Domain\Enum\PieceType;
use Src\ChessGame\Domain\ValueObject\GameAggregateId;
use Src\ChessGame\Domain\ValueObject\Position;
use Symfony\Component\HttpFoundation\Response;

final class PromotePawnController extends Controller
{
    public function __construct(private readonly IGameHandler $gameHandler)
    {
    }

    #[OA\Post(
        path: '/v1/game/{gameId}/promote-pawn',
        description: 'Promote a pawn in a game',
        summary: 'Promote a pawn in the game of chess',
        requestBody: new OA\RequestBody(
            description: 'Request body parameters',
            required: true,
            content: [
                'application/json' => new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(
                        properties: [
                            new OA\Property(
                                property: 'position',
                                description: 'Position',
                                type: 'string',
                            ),
                            new OA\Property(
                                property: 'piece_type',
                                description: 'Piece type to promote to',
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
                response: 204,
                description: 'Successfully promoted the pawn in the game of chess',
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
    public function __invoke(Request $request, string $gameUuid): JsonResponse
    {
        $validated = $request->validate([
            'position' => 'required',
            'piece_type' => 'required',
        ]);

        $this->gameHandler->handlePromotePawn(new PromotePawnCommand(
            GameAggregateId::fromString($gameUuid),
            Position::fromMove($validated['position']),
            PieceType::from($validated['pieceType'])
        ));

        return new JsonResponse(status: Response::HTTP_NO_CONTENT);
    }
}
