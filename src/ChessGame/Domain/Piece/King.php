<?php

declare(strict_types=1);

namespace Domain\ChessGame\Domain\Piece;

use Domain\ChessGame\Domain\Board;
use Domain\ChessGame\Domain\Enum;
use Domain\ChessGame\Domain\ValueObject\Position;

final class King extends Piece
{
    public function __construct(
        Enum\Color $color,
        public bool $hasMoved = false,
        public bool $hasCastled = false
    ) {
        parent::__construct($color, Enum\PieceType::King);
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

        $diffX = abs($endX - $startX);
        $diffY = abs($endY - $startY);

        $castleTopLeft = $this->color === Enum\Color::Black &&
            ($startX === 4 && $startY === 7 && $endX === 2 && $endY === 7);
        $castleTopRight = $this->color === Enum\Color::Black &&
            ($startX === 4 && $startY === 7 && $endX === 6 && $endY === 7);
        $castleBottomLeft = $this->color === Enum\Color::White &&
            ($startX === 4 && $startY === 0 && $endX === 2 && $endY === 0);
        $castleBottomRight = $this->color === Enum\Color::White &&
            ($startX === 4 && $startY === 0 && $endX === 6 && $endY === 0);

        if (!$this->hasMoved && ($castleTopLeft || $castleTopRight || $castleBottomLeft || $castleBottomRight)) {
            $tempStartX = $startX;
            while ($tempStartX !== $endX) {
                if ($tempStartX > $endX) {
                    --$tempStartX;
                } else {
                    ++$tempStartX;
                }

                if (
                    abs($startX - $tempStartX) < 3 &&
                    $board->canTakePosition(
                        Enum\Color::getOppositeColor($this->color),
                        new Position($tempStartX, $startY)
                    )
                ) {
                    return false;
                }

                if (!is_null($board->getSquareByPosition($endPosition)->piece)) {
                    return false;
                }
            }

            $this->hasCastled = true;
            return true;
        }

        if ($diffX > 1 || $diffY > 1) {
            return false;
        }

        return !($board->squares[$endY][$endX]->piece instanceof Piece &&
            !$this->isOpposingPiece($board->squares[$endY][$endX]->piece));
    }

    /**
     * @return Position[]
     */
    public static function moves(Position $position, Board $board): array
    {
        $square = $board->getSquareByPosition($position);
        $positions = [
            new Position($position->x - 1, $position->y + 1), // left-up
            new Position($position->x, $position->y + 1), // up
            new Position($position->x + 1, $position->y + 1), // right-up
            new Position($position->x - 1, $position->y), // left
            new Position($position->x + 1, $position->y), // right
            new Position($position->x - 1, $position->y - 1), // left-down
            new Position($position->x, $position->y - 1), // down
            new Position($position->x + 1, $position->y - 1), // right-down
        ];


        foreach ($positions as $key => $p) {
            if (
                !$board->positionIsOnBoard($p) || (
                    !is_null($board->getSquareByPosition($p)->piece)
                    && $board->getSquareByPosition($p)->piece->color === $square->piece->color
                )
            ) {
                unset($positions[$key]);
            } else {
                $positions[$key] = $p;
            }
        }

        return $positions;
    }

    #[\Override]
    public function availableMoves(Position $position, Board $board): array
    {
        return array_values(self::moves($position, $board));
    }
}
