<?php

declare(strict_types=1);

namespace Tests\Unit\src\ChessGame\Domain\Piece;

use Domain\ChessGame\Domain\Enum\Color;
use Domain\ChessGame\Domain\Enum\PieceType;
use Domain\ChessGame\Domain\Piece\King;
use Domain\ChessGame\Domain\Piece\Piece;
use PHPUnit\Framework\TestCase;

class PieceTest extends TestCase
{
    public function testFromPieceTypeFunctionReturnsInitializedPieceClassByPieceType(): void
    {
        $color = Color::White;
        $pieceType = PieceType::King;

        /** @var King $piece */
        $piece = Piece::fromPieceType($color, $pieceType);

        $this->assertSame($pieceType, $piece->type);
    }
}
