<?php

declare(strict_types=1);

namespace Domain\ChessGame\Application\Commands;

use Domain\ChessGame\Domain\ValueObject\GameAggregateId;

final readonly class ContinueGameCommand
{
    public function __construct(
        public GameAggregateId $gameAggregateId,
    ) {
    }
}
