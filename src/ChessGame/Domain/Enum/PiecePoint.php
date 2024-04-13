<?php

declare(strict_types=1);

namespace Src\ChessGame\Domain\Enum;

enum PiecePoint: int
{
    case Pawn = 1;
    case KnightOrBishop = 3;
    case Rook = 5;
    case Queen = 9;
    case King = 0;
}
