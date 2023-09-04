<?php

declare(strict_types=1);

namespace Domain\ChessGame\Domain\Events;

use Domain\ChessGame\Domain\Enum\Color;
use EventSauce\EventSourcing\Serialization\SerializablePayload;

final class DrawDeclined implements SerializablePayload
{
    public function __construct(
        public readonly Color $decliningColor
    ) {
    }

    public function toPayload(): array
    {
        return [
            'declining_color' => $this->decliningColor->value,
        ];
    }

    public static function fromPayload(array $payload): static
    {
        return new self(
            Color::from($payload['declining_color'])
        );
    }
}
