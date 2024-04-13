<?php

declare(strict_types=1);

namespace Src\ChessGame\Application\Commands;

use Src\ChessGame\Domain\ValueObject\GameAggregateId;
use Src\ChessGame\Domain\ValueObject\Player;

final readonly class StartGameCommand
{
    public function __construct(
        public GameAggregateId $gameAggregateId,
    ) {
    }
}
