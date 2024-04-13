<?php

declare(strict_types=1);

namespace Src\ChessGame\Domain\ValueObject;

final readonly class Move
{
    public function __construct(
        public Position $from,
        public Position $to,
    ) {
    }
}
