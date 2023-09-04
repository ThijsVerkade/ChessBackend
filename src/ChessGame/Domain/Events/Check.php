<?php

declare(strict_types=1);

namespace Domain\ChessGame\Domain\Events;

use Domain\ChessGame\Domain\Enum\Color;
use Domain\ChessGame\Domain\ValueObject\Position;
use EventSauce\EventSourcing\Serialization\SerializablePayload;

final class Check implements SerializablePayload
{
    public function __construct(
        public readonly Color $color,
        public readonly Position $position
    ) {
    }

    public function toPayload(): array
    {
        return [
            'checked_color' => $this->color->value,
            'checked_from_position' => $this->position->toString(),
        ];
    }

    public static function fromPayload(array $payload): static
    {
        return new self(
            Color::from($payload['checked_color']),
            Position::fromString($payload['checked_from_position']),
        );
    }
}
