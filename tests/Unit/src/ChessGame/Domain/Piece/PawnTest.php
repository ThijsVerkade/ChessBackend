<?php

declare(strict_types=1);

namespace Tests\Unit\src\ChessGame\Domain\Piece;

use Domain\ChessGame\Domain\Board;
use Domain\ChessGame\Domain\Piece\Knight;
use Domain\ChessGame\Domain\Piece\Pawn;
use Domain\ChessGame\Domain\ValueObject\Position;
use PHPUnit\Framework\TestCase;

final class PawnTest extends TestCase
{
    // ['R', 'N', 'B', 'Q', 'K', 'B', 'N', 'R']
    // ['P', 'P', 'P', 'P', 'P', 'P', 'P', 'P']
    // [' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ']
    // [' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ']
    // [' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ']
    // [' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ']
    // ['p', 'p', 'p', 'p', 'p', 'p', 'p', 'p']
    // ['r', 'n', 'b', 'q', 'k', 'b', 'n', 'r']
    public function testAvailableMoves(): void
    {
        $position = Position::fromMove('d2');

        $board = Board::setupBoard();
        $knight = $board->getSquareByPosition($position)->piece;

        $this->assertInstanceOf(Pawn::class, $knight);

        $availableMoves = $knight->availableMoves($position, $board);
        var_dump($availableMoves);
        $this->assertCount(2, $availableMoves);

        $this->assertContainsOnlyInstancesOf(Position::class, $availableMoves);

        $this->assertContainsEquals(Position::fromMove('d3'), $availableMoves);
        $this->assertContainsEquals(Position::fromMove('d4'), $availableMoves);
    }
}
