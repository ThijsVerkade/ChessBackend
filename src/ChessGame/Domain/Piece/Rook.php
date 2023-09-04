<?php

declare(strict_types=1);

namespace Domain\ChessGame\Domain\Piece;

use Domain\ChessGame\Domain\Board;
use Domain\ChessGame\Domain\Enum;
use Domain\ChessGame\Domain\ValueObject\Position;

final class Rook extends Piece
{
    public function __construct(
        Enum\Color $color,
        public bool $hasMoved = false,
    ) {
        parent::__construct($color, Enum\PieceType::Rook);
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

        // Check if the move is horizontal or vertical
        if ($startX !== $endX && $startY !== $endY) {
            return false;
        }

        // Check if there are any pieces blocking the path
        if ($startX === $endX) {
            // Move is vertical
            $step = ($startY < $endY) ? 1 : -1;
            for ($y = $startY + $step; $y !== $endY; $y += $step) {
                if ($board->squares[$y][$startX]->piece instanceof Piece) {
                    return false;
                }
            }
        } else {
            // Move is horizontal
            $step = ($startX < $endX) ? 1 : -1;
            for ($x = $startX + $step; $x !== $endX; $x += $step) {
                if ($board->squares[$startY][$x]->piece instanceof Piece) {
                    return false;
                }
            }
        }

        // Check if color is not the same
        if (!$withoutOwnColor) {
            // Move is valid
            return true;
        }

        if (!$endSquare->piece instanceof Piece) {
            // Move is valid
            return true;
        }

        // Move is valid
        return $endSquare->piece->color !== $this->color;
    }
}
