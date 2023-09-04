<?php

declare(strict_types=1);

namespace Domain\ChessGame\Domain\Piece;

use Domain\ChessGame\Domain\Enum;

abstract class Piece implements PieceInterface
{
    public function __construct(
        public readonly Enum\Color $color,
        public readonly Enum\PieceType $type,
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
}
