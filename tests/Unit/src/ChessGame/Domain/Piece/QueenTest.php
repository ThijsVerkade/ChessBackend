<?php

declare(strict_types=1);

namespace Tests\Unit\src\ChessGame\Domain\Piece;

use Src\ChessGame\Domain\Board;
use Src\ChessGame\Domain\Piece\Knight;
use Src\ChessGame\Domain\Piece\Queen;
use Src\ChessGame\Domain\Piece\Rook;
use Src\ChessGame\Domain\ValueObject\Position;
use PHPUnit\Framework\TestCase;

class QueenTest extends TestCase
{
    // ['R', 'N', 'B', 'Q', 'K', 'B', 'N', 'R']
    // ['P', 'P', 'P', 'P', 'P', 'P', 'P', 'P']
    // [' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ']
    // [' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ']
    // [' ', ' ', ' ', 'q', ' ', ' ', ' ', ' ']
    // [' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ']
    // ['p', 'p', 'p', 'p', 'p', 'p', 'p', 'p']
    // ['r', 'n', 'b', ' ', 'k', 'b', 'n', 'r']
    public function testAvailableMoves(): void
    {
        $position = Position::fromMove('d4');

        $board = Board::setupBoard();
        $board->movePiece(
            Position::fromMove('d1'),
            $position,
        );
        $queen = $board->getSquareByPosition($position)->piece;

        $this->assertInstanceOf(Queen::class, $queen);

        $availableMoves = $queen->availableMoves($position, $board);

        $this->assertCount(19, $availableMoves);

        $this->assertContainsOnlyInstancesOf(Position::class, $availableMoves);

        $this->assertContainsEquals(Position::fromMove('a4'), $availableMoves);
        $this->assertContainsEquals(Position::fromMove('b4'), $availableMoves);
        $this->assertContainsEquals(Position::fromMove('c4'), $availableMoves);
        $this->assertContainsEquals(Position::fromMove('e4'), $availableMoves);
        $this->assertContainsEquals(Position::fromMove('f4'), $availableMoves);
        $this->assertContainsEquals(Position::fromMove('g4'), $availableMoves);
        $this->assertContainsEquals(Position::fromMove('h4'), $availableMoves);
        $this->assertNotContainsEquals(Position::fromMove('d2'), $availableMoves);
        $this->assertContainsEquals(Position::fromMove('d3'), $availableMoves);
        $this->assertNotContainsEquals(Position::fromMove('d4'), $availableMoves);
        $this->assertContainsEquals(Position::fromMove('d5'), $availableMoves);
        $this->assertContainsEquals(Position::fromMove('d6'), $availableMoves);
        $this->assertContainsEquals(Position::fromMove('d7'), $availableMoves);
        $this->assertNotContainsEquals(Position::fromMove('b2'), $availableMoves);
        $this->assertContainsEquals(Position::fromMove('c3'), $availableMoves);
        $this->assertContainsEquals(Position::fromMove('e3'), $availableMoves);
        $this->assertNotContainsEquals(Position::fromMove('f2'), $availableMoves);
        $this->assertContainsEquals(Position::fromMove('a7'), $availableMoves);
        $this->assertContainsEquals(Position::fromMove('b6'), $availableMoves);
        $this->assertContainsEquals(Position::fromMove('c5'), $availableMoves);
        $this->assertContainsEquals(Position::fromMove('e5'), $availableMoves);
        $this->assertContainsEquals(Position::fromMove('f6'), $availableMoves);
        $this->assertContainsEquals(Position::fromMove('g7'), $availableMoves);
    }
}
