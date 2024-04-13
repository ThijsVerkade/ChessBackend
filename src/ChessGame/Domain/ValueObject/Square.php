<?php

declare(strict_types=1);

namespace Src\ChessGame\Domain\ValueObject;

use Src\ChessGame\Domain\Enum\Color;
use Src\ChessGame\Domain\Piece\Piece;

final class Square
{
    public function __construct(
        public readonly Position $position,
        public readonly Color $color,
        public ?Piece $piece = null,
        public readonly bool $available = false
    ) {
    }
}
