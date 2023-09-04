<?php

declare(strict_types=1);

namespace Domain\ChessGame\Domain;

use Domain\ChessGame\Domain\Enum\Color;
use Domain\ChessGame\Domain\Enum\PieceType;
use Domain\ChessGame\Domain\Piece\Bishop;
use Domain\ChessGame\Domain\Piece\King;
use Domain\ChessGame\Domain\Piece\Knight;
use Domain\ChessGame\Domain\Piece\Pawn;
use Domain\ChessGame\Domain\Piece\Piece;
use Domain\ChessGame\Domain\Piece\Queen;
use Domain\ChessGame\Domain\Piece\Rook;
use Domain\ChessGame\Domain\ValueObject\Move;
use Domain\ChessGame\Domain\ValueObject\Position;
use Domain\ChessGame\Domain\ValueObject\Square;
use Exception;

final class Board
{
    private const TOP_BOARD = 7;

    private const BOTTOM_BOARD = 0;

    /**
     * @param Square[][] $squares
     */
    public function __construct(
        public array $squares,
        public ?Position $lastMoveFrom = null,
        public ?Position $lastMoveTo = null,
        public ?Position $removedPiece = null,
        public ?Position $checkedFrom = null,
        public ?Move $kingCastling = null,
        public Position $blackKingSquare = new Position(4, 7),
        public Position $whiteKingSquare = new Position(4, 0),
    ) {
    }

    public static function setupBoard(): self
    {
        $board = [];
        $colors = [Color::Black, Color::White];

        for ($row = 0; $row < 8; ++$row) {
            $board[$row] = [];

            for ($col = 0; $col < 8; ++$col) {
                $color = $colors[(($row + $col) % 2)];
                $board[$row][$col] = new Square(new Position($col, $row), $color, null);
            }
        }

        // Set up pawns
        for ($i = 0; $i < 8; ++$i) {
            $board[1][$i]->piece = new Pawn(Color::White);
            $board[6][$i]->piece = new Pawn(Color::Black);
        }

        // Set up rooks
        $board[0][0]->piece = new Rook(Color::White);
        $board[0][7]->piece = new Rook(Color::White);
        $board[7][0]->piece = new Rook(Color::Black);
        $board[7][7]->piece = new Rook(Color::Black);

        // Set up knights
        $board[0][1]->piece = new Knight(Color::White);
        $board[0][6]->piece = new Knight(Color::White);
        $board[7][1]->piece = new Knight(Color::Black);
        $board[7][6]->piece = new Knight(Color::Black);
        $board[0][2]->piece = new Bishop(Color::White);
        $board[0][5]->piece = new Bishop(Color::White);
        $board[7][2]->piece = new Bishop(Color::Black);
        $board[7][5]->piece = new Bishop(Color::Black);

        // Set up queens
        $board[0][3]->piece = new Queen(Color::White);
        $board[7][3]->piece = new Queen(Color::Black);

        // Set up kings
        $board[0][4]->piece = new King(Color::White);
        $board[7][4]->piece = new King(Color::Black);

        return new self($board);
    }

    public function getSquareByPosition(Position $position): Square
    {
        if (!$this->positionIsOnBoard($position)) {
            throw new Exception('Position is not on board');
        }

        return $this->squares[$position->y][$position->x];
    }

    /**
     * @return Square[]
     */
    public function getPiece(PieceType $pieceType, Color $color): array
    {
        $squares = [];

        foreach ($this->squares as $squareY) {
            foreach ($squareY as $square) {
                if (is_null($square->piece)) {
                    continue;
                }

                if ($square->piece->type !== $pieceType) {
                    continue;
                }

                if ($square->piece->color !== $color) {
                    continue;
                }

                $squares[] = $square;
            }
        }

        return $squares;
    }

    public function movePiece(Position $startPosition, Position $endPosition): void
    {
        $startSquare = $this->squares[$startPosition->y][$startPosition->x];
        $this->lastMoveFrom = $startPosition;
        $this->lastMoveTo = $endPosition;

        if (
            $startSquare->piece instanceof Rook ||
            $startSquare->piece instanceof King
        ) {
            $startSquare->piece->hasMoved = true;
        }

        if ($startSquare->piece instanceof King) {
            if ($startSquare->piece->color === Color::White) {
                $this->whiteKingSquare = $endPosition;
            } else {
                $this->blackKingSquare = $endPosition;
            }
        }

        $this->squares[$endPosition->y][$endPosition->x]->piece = $startSquare->piece;
        $this->squares[$startPosition->y][$startPosition->x]->piece = null;
    }

    public function removePiece(Position $position): void
    {
        $this->squares[$position->y][$position->x]->piece = null;
        $this->removedPiece = $position;
    }

    public function getCastleRookMove(Position $startPosition, Position $endPosition): Move
    {
        $startX = $startPosition->x;
        $startY = $startPosition->y;
        $endX = $endPosition->x;
        $endY = $endPosition->y;

        $startPiece = $this->getSquareByPosition($startPosition)->piece;

        $castleTopLeft = $startPiece->color === Enum\Color::Black &&
            ($startX === 4 && $startY === 7 && $endX === 2 && $endY === 7);
        $castleTopRight = $startPiece->color === Enum\Color::Black &&
            ($startX === 4 && $startY === 7 && $endX === 6 && $endY === 7);
        $castleBottomLeft = $startPiece->color === Enum\Color::White &&
            ($startX === 4 && $startY === 0 && $endX === 2 && $endY === 0);
        $castleBottomRight = $startPiece->color === Enum\Color::White &&
            ($startX === 4 && $startY === 0 && $endX === 6 && $endY === 0);

        if ($castleTopLeft) {
            $from = new Position(0, 7);
            $to = new Position(3, 7);
        } elseif ($castleTopRight) {
            $from = new Position(7, 7);
            $to = new Position(5, 7);
        } elseif ($castleBottomLeft) {
            $from = new Position(0, 0);
            $to = new Position(3, 0);
        } elseif ($castleBottomRight) {
            $from = new Position(7, 0);
            $to = new Position(5, 0);
        } else {
            throw new Exception('Unable to castle rook');
        }

        return new Move($from, $to);
    }

    public function pieceGivesCheck(Square $square): bool
    {
        $piece = $square->piece;

        $king = current($this->getPiece(PieceType::King, $piece->color === Color::White ? Color::Black : Color::White));

        return $piece->canMove($square->position, $king->position, $this);
    }

    public function colorGivesCheck(Color $color): bool
    {
        $oppositeKing = current($this->getPiece(PieceType::King, Color::getOppositeColor($color)));

        return $this->canTakePosition($color, $oppositeKing->position);
    }

    public function positionIsOnBoard(Position $position): bool
    {
        return ($position->x >= 0 && $position->x < 8 && $position->y >= 0 && $position->y < 8);
    }

    public function isCheckMate(Color $color): bool
    {
        $oppositeKing = $color === Color::White ? $this->whiteKingSquare : $this->blackKingSquare;
        $oppositeKingSquare = $this->getSquareByPosition($oppositeKing);
        $availableMoves = [];

        foreach (King::moves($oppositeKingSquare->position, $this) as $position) {
            if (!$this->canTakePosition(Color::getOppositeColor($color), $position, false)) {
                $availableMoves[] = $position;
            }
        }

        return $availableMoves === [];
    }

    public function canTakePosition(Color $color, Position $position, bool $withoutOwnColor = true): bool
    {
        foreach ($this->squares as $squareY) {
            foreach ($squareY as $square) {
                if (is_null($square->piece)) {
                    continue;
                }

                if ($square->piece->color !== $color) {
                    continue;
                }

                if ($square->piece->canMove($square->position, $position, $this, $withoutOwnColor)) {
                    return true;
                }
            }
        }

        return false;
    }

    public function positionNeedsPawnPromotion(Position $position): bool
    {
        $square = $this->getSquareByPosition($position);

        if ($square->piece->type !== PieceType::Pawn) {
            return false;
        }

        if ($square->color === Color::White && $position->y === self::TOP_BOARD) {
            return true;
        }

        return $square->color === Color::Black && $position->y === self::BOTTOM_BOARD;
    }

    public function promotePawn(Position $position, PieceType $pieceType): Piece
    {
        if ($pieceType === PieceType::King) {
            throw new Exception('Piece can become a king');
        }

        $square = $this->getSquareByPosition($position);

        if ($square->piece->type !== PieceType::Pawn) {
            throw new Exception('Piece is not a pawn');
        }

        $square->piece = Piece::fromPieceType($square->color, $pieceType);

        return $square->piece;
    }
}
