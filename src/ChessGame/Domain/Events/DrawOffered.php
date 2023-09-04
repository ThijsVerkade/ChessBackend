<?php

declare(strict_types=1);

namespace Domain\ChessGame\Domain\Events;

use Domain\ChessGame\Domain\Enum\Color;
use EventSauce\EventSourcing\Serialization\SerializablePayload;

final class DrawOffered implements SerializablePayload
{
    public function __construct(
        public readonly Color $offeringColor
    ) {
    }

    public function toPayload(): array
    {
        return [
            'offering_color' => $this->offeringColor->value,
        ];
    }

    public static function fromPayload(array $payload): static
    {
        return new self(
            Color::from($payload['offering_color'])
        );
    }
}
