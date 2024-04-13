<?php

declare(strict_types=1);

namespace Domain\ChessGame\Domain\Events;

use Domain\ChessGame\Domain\Enum\Color;
use EventSauce\EventSourcing\Serialization\SerializablePayload;

final readonly class GameEndedInResign implements SerializablePayload
{
    public function __construct(
        public Color $resignedColor
    ) {
    }

    #[\Override]
    public function toPayload(): array
    {
        return [
            'resigned_color' => $this->resignedColor->value
        ];
    }

    #[\Override]
    public static function fromPayload(array $payload): static
    {
        return new self(
            Color::from($payload['resigned_color'])
        );
    }
}
