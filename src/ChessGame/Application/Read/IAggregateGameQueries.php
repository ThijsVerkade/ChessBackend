<?php

declare(strict_types=1);

namespace Domain\ChessGame\Application\Read;

use Domain\ChessGame\Domain\ValueObject\GameAggregateId;

interface IAggregateGameQueries
{
    public function getEventsByAggregateId(GameAggregateId $aggregateId): array;
}
