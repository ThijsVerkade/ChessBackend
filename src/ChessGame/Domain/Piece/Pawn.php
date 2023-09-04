<?php

declare(strict_types=1);

namespace Domain\ChessGame\Domain\Piece;

use Domain\ChessGame\Domain;
use Domain\ChessGame\Domain\Enum;
use Domain\ChessGame\Domain\ValueObject\Position;

class Pawn extends Piece
{
    public function __construct(
        Enum\Color $color,
        public ?Position $takenPiecePosition = null,
    ) {
        parent::__construct($color, Enum\PieceType::Pawn);
    }

    public function canMove(
        Position $startPosition,
        Position $endPosition,
        Domain\Board $board,
        bool $withoutOwnColor = true
    ): bool {
        $startX = $startPosition->x;
        $startY = $startPosition->y;
        $endX = $endPosition->x;
        $endY = $endPosition->y;
        $endSquare = $board->getSquareByPosition($endPosition);

        if (abs($startX - $endX) === 0 && is_null($endSquare->piece)) {
            if (abs($startY - $endY) === 1) {
                return true;
            }

            if (
                ($startY === 1 || $startY === 6) &&
                (
                    (
                        $startY - $endY === 2 &&
                        $this->color === Enum\Color::Black &&
                        is_null($board->squares[$startY - 1][$startX]->piece)) ||
                    (
                        $startY - $endY === -2 &&
                        $this->color === Enum\Color::White &&
                        is_null($board->squares[$startY + 1][$startX]->piece)
                    )
                )
            ) {
                return true;
            }
        }

        if (
            abs($startX - $endX) === 1 &&
            (
                ($startY - $endY === 1 && $this->color === Enum\Color::Black) ||
                ($startY - $endY === -1 && $this->color === Enum\Color::White)
            )
        ) {
            if (!is_null($endSquare->piece)) {
                return $endSquare->piece->color !== $this->color && $withoutOwnColor;
            }

            if (is_null($board->lastMoveTo) && is_null($board->lastMoveFrom)) {
                return false;
            }

            $square = $board->squares[$board->lastMoveTo->y][$board->lastMoveTo->x];
            if (
                $square->piece instanceof Pawn
                && $square->piece->color !== $this->color
                && (
                    ($board->lastMoveTo->y - $board->lastMoveFrom->y === 2
                    && $this->color === Enum\Color::Black)
                    || ($board->lastMoveTo->y - $board->lastMoveFrom->y === -2
                    && $this->color === Enum\Color::White)
                )
            ) {
                $this->takenPiecePosition = $board->lastMoveTo;
                return true;
            }
        }

        return false;
    }
}
