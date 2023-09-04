<?php

declare(strict_types=1);

namespace Domain\ChessGame\Domain\Events;

use Domain\ChessGame\Domain\Enum\Color;
use EventSauce\EventSourcing\Serialization\SerializablePayload;

final class GameEndedInResign implements SerializablePayload
{
    public function __construct(
        public readonly Color $resignedColor
    ) {
    }

    public function toPayload(): array
    {
        return [
            'resigned_color' => $this->resignedColor->value
        ];
    }

    public static function fromPayload(array $payload): static
    {
        return new self(
            Color::from($payload['resigned_color'])
        );
    }
}
