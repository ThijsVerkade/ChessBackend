<?php

declare(strict_types=1);

namespace Tests\Unit\src\ChessGame\Domain\Piece;

use Src\ChessGame\Domain\Board;
use Src\ChessGame\Domain\Piece\Knight;
use Src\ChessGame\Domain\Piece\Rook;
use Src\ChessGame\Domain\ValueObject\Position;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Rook::class)]
final class RookTest extends TestCase
{
    // ['R', 'N', 'B', 'Q', 'K', 'B', 'N', 'R']
    // ['P', 'P', 'P', 'P', 'P', 'P', 'P', 'P']
    // [' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ']
    // [' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ']
    // [' ', ' ', ' ', 'r', ' ', ' ', ' ', ' ']
    // [' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ']
    // ['p', 'p', 'p', 'p', 'p', 'p', 'p', 'p']
    // [' ', 'n', 'b', 'q', 'k', 'b', 'n', 'r']
    public function testAvailableMoves(): void
    {
        $position = Position::fromMove('d4');

        $board = Board::setupBoard();
        $board->movePiece(
            Position::fromMove('a1'),
            $position,
        );
        $rook = $board->getSquareByPosition($position)->piece;

        $this->assertInstanceOf(Rook::class, $rook);

        $availableMoves = $rook->availableMoves($position, $board);

        $this->assertCount(11, $availableMoves);

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
    }
}
