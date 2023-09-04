<?php

declare(strict_types=1);

namespace Domain\ChessGame\Application\Read\Models;

final class AggregateGame
{
    public function __construct(
        public readonly string $eventId,
        public readonly string $aggregateRootId,
        public readonly int $version,
        public readonly string $payload,
    ) {
    }
}
