<?php

declare(strict_types=1);

namespace Src\ChessGame\Domain\Piece;

use Src\ChessGame\Domain\Board;
use Src\ChessGame\Domain\Enum;
use Src\ChessGame\Domain\ValueObject\Position;

abstract class Piece implements PieceInterface
{
    public function __construct(
        public readonly Enum\Color $color,
        public readonly Enum\PieceType $pieceType,
    ) {
    }

    protected function isOpposingPiece(Piece $piece): bool
    {
        return $piece->color !== $this->color;
    }

    public static function fromPieceType(
        Enum\Color $color,
        Enum\PieceType $pieceType
    ): Pawn|Knight|Bishop|Rook|Queen|King {
        $piece = match ($pieceType) {
            Enum\PieceType::Pawn => Pawn::class,
            Enum\PieceType::Knight => Knight::class,
            Enum\PieceType::Bishop => Bishop::class,
            Enum\PieceType::Rook => Rook::class,
            Enum\PieceType::Queen => Queen::class,
            Enum\PieceType::King => King::class,
        };

        return new $piece($color);
    }

    /**
     * @return Position[]
     */
    public function getMovesOfPositionByDirections(array $directions, Position $position, Board $board): array
    {
        $row = $position->x;
        $col = $position->y;
        $moves = [];

        foreach ($directions as [$dx, $dy]) {
            for ($i = 1; $i <= 7; ++$i) {
                $newRow = $row + $i * $dx;
                $newCol = $col + $i * $dy;

                if ($newRow < 0 || $newRow > 7 || $newCol < 0 || $newCol > 7) {
                    break;
                }

                $targetSquare = $board->getSquareByPosition(new Position($newRow, $newCol));

                if (is_null($targetSquare->piece)) {
                    $moves[] = $targetSquare->position;
                    continue;
                }

                if ($targetSquare->piece->color !== $this->color) {
                    $moves[] = $targetSquare->position;
                }

                break;
            }
        }

        return $moves;
    }
}
