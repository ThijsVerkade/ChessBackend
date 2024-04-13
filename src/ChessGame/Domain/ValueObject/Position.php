<?php

declare(strict_types=1);

namespace Domain\ChessGame\Domain\ValueObject;

use Exception;

final readonly class Position
{
    public function __construct(
        public int $x,
        public int $y,
    ) {
    }

    public static function fromMove(string $move): self
    {
        if (!preg_match('/^([a-h][1-8])$/', $move, $matches)) {
            throw new Exception('Invalid move format');
        }

        $x = ord($matches[1][0]) - ord('a');
        $y = (int) $matches[1][1] - 1;

        return new Position($x, $y);
    }

    public function toString(): string
    {
        return $this->x . $this->y;
    }

    public static function fromString(string $position): static
    {
        $position = str_split($position);

        return new self((int)$position[0], (int)$position[1]);
    }
}
