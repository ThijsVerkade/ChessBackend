<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Game;

use App\Http\Controllers\Controller;
use Domain\ChessGame\Application\Read\IAggregateGameQueries;
use Domain\ChessGame\Domain\ValueObject\GameAggregateId;
use Illuminate\Http\JsonResponse;

final class RetrieveEventsController extends Controller
{
    public function __construct(
        private readonly IAggregateGameQueries $aggregateGameQueries,
    ) {
    }

    public function __invoke(string $gameId): JsonResponse
    {
        $results = $this->aggregateGameQueries
            ->getEventsByAggregateId(GameAggregateId::fromString($gameId));

        return new JsonResponse([
            'data' => $results,
        ]);
    }
}
