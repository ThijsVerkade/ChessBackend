<?php

declare(strict_types=1);

namespace Src\ChessGame\Application\Commands;

use Src\ChessGame\Domain\Enum\PieceType;
use Src\ChessGame\Domain\ValueObject\GameAggregateId;
use Src\ChessGame\Domain\ValueObject\Position;

final readonly class PromotePawnCommand
{
    public function __construct(
        public GameAggregateId $gameAggregateId,
        public Position $position,
        public PieceType $pieceType,
    ) {
    }
}
