<?php

declare(strict_types=1);

namespace Src\ChessGame\Domain\Events;

use Src\ChessGame\Domain\Enum\Color;
use EventSauce\EventSourcing\Serialization\SerializablePayload;

final readonly class DrawOffered implements SerializablePayload
{
    public function __construct(
        public Color $offeringColor
    ) {
    }

    #[\Override]
    public function toPayload(): array
    {
        return [
            'offering_color' => $this->offeringColor->value,
        ];
    }

    #[\Override]
    public static function fromPayload(array $payload): static
    {
        return new self(
            Color::from($payload['offering_color'])
        );
    }
}
