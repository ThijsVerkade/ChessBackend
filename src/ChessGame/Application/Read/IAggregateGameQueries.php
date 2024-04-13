<?php

declare(strict_types=1);

namespace Src\ChessGame\Application\Read;

use Src\ChessGame\Domain\ValueObject\GameAggregateId;

interface IAggregateGameQueries
{
    public function getEventsByAggregateId(GameAggregateId $gameAggregateId): array;
}
