<?php

declare(strict_types=1);

namespace Tests\Unit\src\ChessGame\Domain\Piece;

use Src\ChessGame\Domain\Board;
use Src\ChessGame\Domain\Piece\Knight;
use Src\ChessGame\Domain\ValueObject\Position;
use PHPUnit\Framework\TestCase;

final class KnightTest extends TestCase
{
    // ['R', 'N', 'B', 'Q', 'K', 'B', 'N', 'R']
    // ['P', 'P', 'P', 'P', 'P', 'P', 'P', 'P']
    // [' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ']
    // [' ', ' ', ' ', 'n', ' ', ' ', ' ', ' ']
    // [' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ']
    // [' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ']
    // ['p', 'p', 'p', 'p', 'p', 'p', 'p', 'p']
    // ['r', ' ', 'b', 'q', 'k', 'b', 'n', 'r']
    public function testAvailableMoves(): void
    {
        $position = Position::fromMove('d5');

        $board = Board::setupBoard();
        $board->movePiece(
            Position::fromMove('b1'),
            $position,
        );
        $knight = $board->getSquareByPosition($position)->piece;

        $this->assertInstanceOf(Knight::class, $knight);

        $availableMoves = $knight->availableMoves($position, $board);

        $this->assertCount(8, $availableMoves);

        $this->assertContainsOnlyInstancesOf(Position::class, $availableMoves);

        $this->assertNotContainsEquals(Position::fromMove('b3'), $availableMoves);
        $this->assertContainsEquals(Position::fromMove('c3'), $availableMoves);
        $this->assertContainsEquals(Position::fromMove('e3'), $availableMoves);
        $this->assertContainsEquals(Position::fromMove('b4'), $availableMoves);
        $this->assertContainsEquals(Position::fromMove('f4'), $availableMoves);
        $this->assertContainsEquals(Position::fromMove('b6'), $availableMoves);
        $this->assertContainsEquals(Position::fromMove('f6'), $availableMoves);
        $this->assertContainsEquals(Position::fromMove('c7'), $availableMoves);
        $this->assertContainsEquals(Position::fromMove('e7'), $availableMoves);
    }
}
