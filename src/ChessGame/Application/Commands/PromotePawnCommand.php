<?php

declare(strict_types=1);

namespace Domain\ChessGame\Application\Commands;

use Domain\ChessGame\Domain\Enum\PieceType;
use Domain\ChessGame\Domain\ValueObject\GameAggregateId;
use Domain\ChessGame\Domain\ValueObject\Position;

final class PromotePawnCommand
{
    public function __construct(
        public readonly GameAggregateId $gameAggregateId,
        public readonly Position $position,
        public readonly PieceType $pieceType,
    ) {
    }
}
