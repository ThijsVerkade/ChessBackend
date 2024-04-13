<?php

declare(strict_types=1);

namespace Domain\ChessGame\Application\Read\Models;

final readonly class AggregateGame
{
    public function __construct(
        public string $eventId,
        public string $aggregateRootId,
        public int $version,
        public string $payload,
    ) {
    }
}
