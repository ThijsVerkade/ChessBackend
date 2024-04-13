<?php

declare(strict_types=1);

namespace Domain\ChessGame\Domain\Events;

use Domain\ChessGame\Domain\Enum\Color;
use Domain\ChessGame\Domain\ValueObject\Position;
use EventSauce\EventSourcing\Serialization\SerializablePayload;

final readonly class Check implements SerializablePayload
{
    public function __construct(
        public Color $color,
        public Position $position
    ) {
    }

    #[\Override]
    public function toPayload(): array
    {
        return [
            'checked_color' => $this->color->value,
            'checked_from_position' => $this->position->toString(),
        ];
    }

    #[\Override]
    public static function fromPayload(array $payload): static
    {
        return new self(
            Color::from($payload['checked_color']),
            Position::fromString($payload['checked_from_position']),
        );
    }
}
