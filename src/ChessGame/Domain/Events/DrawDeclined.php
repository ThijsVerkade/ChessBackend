<?php

declare(strict_types=1);

namespace Src\ChessGame\Domain\Events;

use Src\ChessGame\Domain\Enum\Color;
use EventSauce\EventSourcing\Serialization\SerializablePayload;

final readonly class DrawDeclined implements SerializablePayload
{
    public function __construct(
        public Color $decliningColor
    ) {
    }

    #[\Override]
    public function toPayload(): array
    {
        return [
            'declining_color' => $this->decliningColor->value,
        ];
    }

    #[\Override]
    public static function fromPayload(array $payload): static
    {
        return new self(
            Color::from($payload['declining_color'])
        );
    }
}
