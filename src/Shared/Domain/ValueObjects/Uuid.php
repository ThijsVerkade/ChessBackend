<?php

declare(strict_types=1);

namespace Domain\Shared\Domain\ValueObjects;

use InvalidArgumentException;
use Ramsey\Uuid\Uuid as RamseyUuid;

class Uuid
{
    final private function __construct(
        public readonly string $value
    ) {
        $this->ensureIsValidUuid($value);
    }

    public static function random(): static
    {
        return new static(RamseyUuid::uuid4()->toString());
    }

    public static function fromString(string $value): static
    {
        return new static($value);
    }

    public function toString(): string
    {
        return $this->value;
    }

    private function ensureIsValidUuid(string $value): void
    {
        if (!RamseyUuid::isValid($value)) {
            throw new InvalidArgumentException(sprintf('<%s> does not allow the value <%s>.', static::class, $value));
        }
    }
}
