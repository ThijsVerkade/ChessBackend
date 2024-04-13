<?php

declare(strict_types=1);

namespace Src\ChessGame\Domain\Piece;

use Src\ChessGame\Domain;
use Src\ChessGame\Domain\Board;
use Src\ChessGame\Domain\Enum;
use Src\ChessGame\Domain\ValueObject\Position;
use Exception;

class Pawn extends Piece
{
    public function __construct(
        Enum\Color $color,
        public ?Position $takenPiecePosition = null,
    ) {
        parent::__construct($color, Enum\PieceType::Pawn);
    }

    #[\Override]
    public function canMove(
        Position $startPosition,
        Position $endPosition,
        Src\Board $board,
        bool $withoutOwnColor = true,
        bool $withoutKing = false
    ): bool {
        $startX = $startPosition->x;
        $startY = $startPosition->y;
        $endX = $endPosition->x;
        $endY = $endPosition->y;
        $endSquare = $board->getSquareByPosition($endPosition);

        if (!$endSquare->piece instanceof \Src\ChessGame\Domain\Piece\Piece && abs($startX - $endX) === 0) {
            if (
                (
                    $startY - $endY === 1 &&
                    $this->color === Enum\Color::Black
                ) ||
                (
                    $startY - $endY === -1 &&
                    $this->color === Enum\Color::White
                )
            ) {
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
            (
                ($startY - $endY === 1 && $this->color === Enum\Color::Black) ||
                ($startY - $endY === -1 && $this->color === Enum\Color::White)
            ) &&
            abs($startX - $endX) === 1
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

    #[\Override]
    public function availableMoves(Position $position, Board $board): array
    {
        $moves = [];
        $row = $position->x;
        $col = $position->y;

        $direction = $this->color === Enum\Color::Black ? -1 : 1;

        $moveDirections = [
            [$direction, -1],         // Capture diagonal left
            [$direction, 1],          // Capture diagonal right
        ];

        $newRow = $row;
        $newCol = $col + $direction;

        $targetSquare = $board->getSquareByPosition(new Position($newRow, $newCol));

        if (is_null($targetSquare->piece)) {
            $moves[] = $targetSquare->position;

            // Check initial double move for pawns (only from starting rank)
            $newCol = $col + (2 * $direction);
            if (
                ( $this->color === Enum\Color::Black && $col === 6) ||
                ( $this->color === Enum\Color::White && $col === 1)
            ) {
                $targetSquare = $board->getSquareByPosition(new Position($newRow, $newCol));
                if (is_null($targetSquare->piece)) {
                    $moves[] = $targetSquare->position;
                }
            }
        }

        // Check diagonal captures
        foreach ($moveDirections as [$dy, $dx]) {
            $newRow = $row + $dx;
            $newCol = $col + $dy;

            if ($newRow >= 0 && $newRow <= 7 && $newCol >= 0 && $newCol <= 7) {
                $targetSquare = $board->getSquareByPosition(new Position($newRow, $newCol));
                if (!is_null($targetSquare->piece) && $targetSquare->piece->color !== $this->color) {
                    $moves[] = $targetSquare->position;
                }
            }
        }

        return $moves;
    }
}
