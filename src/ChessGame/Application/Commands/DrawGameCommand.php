<?php

declare(strict_types=1);

namespace Src\ChessGame\Application\Commands;

use Src\ChessGame\Domain\ValueObject\GameAggregateId;

final readonly class DrawGameCommand
{
    public function __construct(
        public GameAggregateId $gameAggregateId,
    ) {
    }
}
