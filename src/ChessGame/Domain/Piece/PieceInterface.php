<?php

declare(strict_types=1);

namespace Src\ChessGame\Domain\Piece;

use Src\ChessGame\Domain\Board;
use Src\ChessGame\Domain\ValueObject\Position;
use Src\ChessGame\Domain\ValueObject\Square;

interface PieceInterface
{
    public function canMove(
        Position $startPosition,
        Position $endPosition,
        Board $board,
        bool $withoutOwnColor = true,
        bool $withoutKing = false
    ): bool;

    public function availableMoves(Position $position, Board $board): array;
}
