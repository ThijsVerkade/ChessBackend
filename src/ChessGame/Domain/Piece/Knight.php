<?php

declare(strict_types=1);

namespace Domain\ChessGame\Domain\Piece;

use Domain\ChessGame\Domain\Board;
use Domain\ChessGame\Domain\Enum;
use Domain\ChessGame\Domain\ValueObject\Position;

final class Knight extends Piece
{
    public function __construct(Enum\Color $color)
    {
        parent::__construct($color, Enum\PieceType::Knight);
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

        // Calculate the absolute difference between the x and y coordinates
        $dx = abs($startX - $endX);
        $dy = abs($startY - $endY);
        // Check if the move is valid for a knight
        if ($dx == 1 && $dy == 2) {
            // Check if the ending position is empty or contains an opposing piece
            // Invalid move
            return !$board->getSquareByPosition($endPosition)->piece instanceof Piece ||
                ($this->isOpposingPiece($board->getSquareByPosition($endPosition)->piece));
        }

        if ($dx != 2) {
            // Invalid move
            return false;
        }

        if ($dy != 1) {
            // Invalid move
            return false;
        }

        // Check if the ending position is empty or contains an opposing piece
        // Invalid move
        return !$board->getSquareByPosition($endPosition)->piece instanceof Piece ||
            ($this->isOpposingPiece($board->getSquareByPosition($endPosition)->piece));
    }

    #[\Override]
    public function availableMoves(Position $position, Board $board): array
    {
        $row = $position->x;
        $col = $position->y;

        $knightMoves = [
            [-2, -1],
            [-2, 1],
            [-1, -2],
            [-1, 2],
            [1, -2],
            [1, 2],
            [2, -1],
            [2, 1],
        ];

        $moves = [];

        foreach ($knightMoves as [$dx, $dy]) {
            $newRow = $row + $dx;
            $newCol = $col + $dy;

            if ($newRow >= 0 && $newRow <= 7 && $newCol >= 0 && $newCol <= 7) {
                $targetSquare = $board->getSquareByPosition(new Position($newRow, $newCol));
                if (is_null($targetSquare->piece) || $targetSquare->piece->color !== $this->color) {
                    $moves[] = $targetSquare->position;
                }
            }
        }

        return $moves;
    }
}
