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

    #[\Override]
    public function canMove(
        Position $startPosition,
        Position $endPosition,
        Board $board,
        bool $withoutOwnColor = true,
        bool $withoutKing = false
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
                if (
                    $board->squares[$y][$startX]->piece instanceof Piece &&
                    (!$withoutKing || $board->squares[$y][$startX]->piece->type !== Enum\PieceType::King)
                ) {
                    return false;
                }
            }
        } else {
            // Move is horizontal
            $step = ($startX < $endX) ? 1 : -1;
            for ($x = $startX + $step; $x !== $endX; $x += $step) {
                if (
                    $board->squares[$startY][$x]->piece instanceof Piece  &&
                    (!$withoutKing || $board->squares[$startY][$x]->piece->type !== Enum\PieceType::King)
                ) {
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

    #[\Override]
    public function availableMoves(Position $position, Board $board): array
    {
        $directions = [
            [-1, 0], // Up
            [1, 0],  // Down
            [0, -1], // Left
            [0, 1],  // Right
        ];

        return $this->getMovesOfPositionByDirections($directions, $position, $board);
    }
}
