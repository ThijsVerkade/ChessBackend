<?php

declare(strict_types=1);

namespace Src\ChessGame\Domain\Enum;

enum Color: string
{
    case Black = 'black';
    case White = 'white';

    public static function getOppositeColor(Color $color): self
    {
        return $color === self::Black ? self::White : self::Black;
    }
}
