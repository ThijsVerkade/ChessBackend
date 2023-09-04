<?php

declare(strict_types=1);

namespace Domain\ChessGame\Domain\Enum;

enum PieceType: string
{
    case Pawn = 'pawn';
    case Knight = 'knight';
    case Bishop = 'bishop';
    case Rook = 'rook';
    case Queen = 'queen';
    case King = 'king';
}
