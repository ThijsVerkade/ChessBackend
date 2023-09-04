<?php

declare(strict_types=1);

namespace Domain\ChessGame\Domain\Events;

use Domain\ChessGame\Domain\Enum\Color;
use EventSauce\EventSourcing\Serialization\SerializablePayload;

final class DrawAccepted implements SerializablePayload
{
    public function __construct(
        public readonly Color $acceptingColor
    ) {
    }

    public function toPayload(): array
    {
        return [
            'accepting_color' => $this->acceptingColor->value
        ];
    }

    public static function fromPayload(array $payload): static
    {
        return new self(
            Color::from($payload['accepting_color'])
        );
    }
}
