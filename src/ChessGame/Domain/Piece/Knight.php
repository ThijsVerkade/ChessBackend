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

        // Calculate the absolute difference between the x and y coordinates
        $dx = abs($startX - $endX);
        $dy = abs($startY - $endY);
        // Check if the move is valid for a knight
        if ($dx == 1 && $dy == 2) {
            // Check if the ending position is empty or contains an opposing piece
            // Invalid move
            return !$board->getSquareByPosition($endPosition)->piece instanceof \Domain\ChessGame\Domain\Piece\Piece ||
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
        return !$board->getSquareByPosition($endPosition)->piece instanceof \Domain\ChessGame\Domain\Piece\Piece ||
            ($this->isOpposingPiece($board->getSquareByPosition($endPosition)->piece));
    }
}
