<?php

declare(strict_types=1);

namespace Domain\ChessGame\Application\Commands;

use Domain\ChessGame\Domain\ValueObject\GameAggregateId;
use Domain\ChessGame\Domain\ValueObject\Player;
use Domain\ChessGame\Domain\ValueObject\Position;

final readonly class GetAvailablePositionsCommand
{
    public function __construct(
        public GameAggregateId $gameAggregateId,
        public Position $position,
    ) {
    }
}
