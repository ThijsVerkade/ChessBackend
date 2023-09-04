<?php

declare(strict_types=1);

namespace Domain\ChessGame\Domain\Piece;

use Domain\ChessGame\Domain\Board;
use Domain\ChessGame\Domain\Enum;
use Domain\ChessGame\Domain\ValueObject\Position;

class Queen extends Piece
{
    public function __construct(Enum\Color $color)
    {
        parent::__construct($color, Enum\PieceType::Queen);
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

        if (
            $withoutOwnColor &&
            $endSquare->piece instanceof \Domain\ChessGame\Domain\Piece\Piece &&
            $endSquare->piece->color === $this->color
        ) {
            return false;
        }

        if ($startX === $endX || $startY === $endY) {
            if ($startX === $endX) {
                $step = ($startY < $endY) ? 1 : -1;
                for ($y = $startY + $step; $y !== $endY; $y += $step) {
                    if ($board->squares[$y][$startX]->piece instanceof \Domain\ChessGame\Domain\Piece\Piece) {
                        return false;
                    }
                }
            } else {
                $step = ($startX < $endX) ? 1 : -1;
                for ($x = $startX + $step; $x !== $endX; $x += $step) {
                    if ($board->squares[$startY][$x]->piece instanceof \Domain\ChessGame\Domain\Piece\Piece) {
                        return false;
                    }
                }
            }

            // Move is valid
            return true;
        }

        if (abs($endX - $startX) === abs($endY - $startY)) {
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

            return true;
        }

        return false;
    }

    /**
     * @return string[]
     */
    public function moves(Position $position, Board $board): array
    {

        $piece = $board->getSquareByPosition($position);

        $moves = [];

        $directions = [
            [-1, -1],
            [-1, 1],
            [1, -1],
            [1, 1],
            [1, 0], // LEFT
            [-1, 0], // RIGHT
            [0, 1], // UP
            [0, -1], // DOWN
        ];

        foreach ($directions as $direction) {
            $i = $position->x + $direction[0];
            $j = $position->y + $direction[1];
            while ($i >= 0 && $i < 8 && $j >= 0 && $j < 8) {
                $square = $board->getSquareByPosition($squarePosition = new Position($i, $j));
                if (is_null($square->piece)) {
                    $moves[] = $squarePosition->toString();
                } else {
                    if ($square->piece->color === $piece->piece->color) {
                        break;
                    }

                    $moves[] = $squarePosition->toString();
                    if ($square->piece->type !== Enum\PieceType::King) {
                        break;
                    }
                }

                $i += $direction[0];
                $j += $direction[1];
            }
        }

        return $moves;
    }
}
