<?php

declare(strict_types=1);

namespace Tests\Unit\src\ChessGame\Domain\Piece;

use Src\ChessGame\Domain\Board;
use Src\ChessGame\Domain\Piece\Bishop;
use Src\ChessGame\Domain\ValueObject\Position;
use PHPUnit\Framework\TestCase;

final class BishopTest extends TestCase
{
    // ['R', 'N', 'B', 'Q', 'K', 'B', 'N', 'R']
    // ['P', 'P', 'P', 'P', 'P', 'P', 'P', 'P']
    // [' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ']
    // [' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ']
    // [' ', ' ', 'b', ' ', ' ', ' ', ' ', ' ']
    // [' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ']
    // ['p', 'p', 'p', 'p', 'p', 'p', 'p', 'p']
    // ['r', 'n', 'b', 'q', 'k', ' ', 'n', 'r']
    public function testAvailableMoves(): void
    {
        $position = Position::fromMove('c4');

        $board = Board::setupBoard();
        $board->movePiece(
            Position::fromMove('f1'),
            $position,
        );
        $bishop = $board->getSquareByPosition($position)->piece;

        $this->assertInstanceOf(Bishop::class, $bishop);

        $availableMoves = $bishop->availableMoves($position, $board);

        $this->assertCount(7, $availableMoves);

        $this->assertContainsOnlyInstancesOf(Position::class, $availableMoves);

        $this->assertNotContainsEquals(Position::fromMove('a2'), $availableMoves);
        $this->assertContainsEquals(Position::fromMove('b3'), $availableMoves);
        $this->assertContainsEquals(Position::fromMove('d3'), $availableMoves);
        $this->assertNotContainsEquals(Position::fromMove('e2'), $availableMoves);
        $this->assertContainsEquals(Position::fromMove('a6'), $availableMoves);
        $this->assertContainsEquals(Position::fromMove('b5'), $availableMoves);
        $this->assertContainsEquals(Position::fromMove('d5'), $availableMoves);
        $this->assertContainsEquals(Position::fromMove('e6'), $availableMoves);
        $this->assertContainsEquals(Position::fromMove('f7'), $availableMoves);
    }
}
