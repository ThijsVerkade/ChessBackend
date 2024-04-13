<?php

declare(strict_types=1);

namespace Src\ChessGame\Domain\Events;

use Src\ChessGame\Domain\Enum\Color;
use EventSauce\EventSourcing\Serialization\SerializablePayload;

final readonly class DrawAccepted implements SerializablePayload
{
    public function __construct(
        public Color $acceptingColor
    ) {
    }

    #[\Override]
    public function toPayload(): array
    {
        return [
            'accepting_color' => $this->acceptingColor->value
        ];
    }

    #[\Override]
    public static function fromPayload(array $payload): static
    {
        return new self(
            Color::from($payload['accepting_color'])
        );
    }
}
