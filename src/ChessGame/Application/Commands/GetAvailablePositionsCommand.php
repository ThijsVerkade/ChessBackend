<?php

declare(strict_types=1);

namespace Src\ChessGame\Application\Commands;

use Src\ChessGame\Domain\ValueObject\GameAggregateId;
use Src\ChessGame\Domain\ValueObject\Player;
use Src\ChessGame\Domain\ValueObject\Position;

final readonly class GetAvailablePositionsCommand
{
    public function __construct(
        public GameAggregateId $gameAggregateId,
        public Position $position,
    ) {
    }
}
