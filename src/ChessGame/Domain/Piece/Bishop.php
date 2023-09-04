<?php

declare(strict_types=1);

namespace Domain\ChessGame\Domain\Piece;

use Domain\ChessGame\Domain\Board;
use Domain\ChessGame\Domain\Enum;
use Domain\ChessGame\Domain\ValueObject\Position;

final class Bishop extends Piece
{
    public function __construct(Enum\Color $color)
    {
        parent::__construct($color, Enum\PieceType::Bishop);
    }

    public function canMove(
        Position $startPosition,
        Position $endPosition,
        Board $board,
        bool $withoutOwnColor = true
    ): bool {
        $startX = $startPosition->x;
        $startY = $startPosition->y;
        $endX = $endPosition->x;
        $endY = $endPosition->y;
        $endSquare = $board->getSquareByPosition($endPosition);

        // Check if the end position is on a diagonal from the start position
        if (abs($endX - $startX) !== abs($endY - $startY)) {
            return false;
        }

        // Check if there are any pieces blocking the bishop's path
        $dirX = ($endX > $startX) ? 1 : -1;
        $dirY = ($endY > $startY) ? 1 : -1;
        $x = $startX + $dirX;
        $y = $startY + $dirY;
        while ($x !== $endX && $y !== $endY) {
            if ($board->squares[$y][$x]->piece instanceof \Domain\ChessGame\Domain\Piece\Piece) {
                return false;
            }

            $x += $dirX;
            $y += $dirY;
        }

        if (!$withoutOwnColor) {
            // If all checks pass, the move is valid
            return true;
        }

        if (!$endSquare->piece instanceof \Domain\ChessGame\Domain\Piece\Piece) {
            // If all checks pass, the move is valid
            return true;
        }
        // If all checks pass, the move is valid
        return $endSquare->piece->color !== $this->color;
    }
}
