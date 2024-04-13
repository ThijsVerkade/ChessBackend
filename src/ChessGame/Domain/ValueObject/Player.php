<?php

declare(strict_types=1);

namespace Src\ChessGame\Domain\ValueObject;

final readonly class Player
{
    public function __construct(
        public string $name,
    ) {
    }
}
