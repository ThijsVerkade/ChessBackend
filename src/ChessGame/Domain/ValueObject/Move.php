<?php

declare(strict_types=1);

namespace Domain\ChessGame\Domain\ValueObject;

final class Move
{
    public function __construct(
        public readonly Position $from,
        public readonly Position $to,
    ) {
    }
}
