<?php

declare(strict_types=1);

namespace Domain\ChessGame\Application\Commands;

use Domain\ChessGame\Domain\ValueObject\GameAggregateId;

final class ContinueGameCommand
{
    public function __construct(
        public readonly GameAggregateId $gameAggregateId,
    ) {
    }
}
