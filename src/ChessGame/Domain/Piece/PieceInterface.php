<?php

declare(strict_types=1);

namespace Domain\ChessGame\Domain\Piece;

use Domain\ChessGame\Domain\Board;
use Domain\ChessGame\Domain\ValueObject\Position;
use Domain\ChessGame\Domain\ValueObject\Square;

interface PieceInterface
{
    public function canMove(
        Position $startPosition,
        Position $endPosition,
        Board $board,
        bool $withoutOwnColor = true
    ): bool;
}
