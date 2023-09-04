<?php

declare(strict_types=1);

namespace Domain\ChessGame\Domain\ValueObject;

use Domain\ChessGame\Domain\Enum\Color;
use Domain\ChessGame\Domain\Piece\Piece;

final class Square
{
    public function __construct(
        public readonly Position $position,
        public readonly Color $color,
        public ?Piece $piece = null
    ) {
    }
}
