<?php

declare(strict_types=1);

namespace Domain\ChessGame\Domain\Events;

use Domain\ChessGame\Domain\Enum\Color;
use EventSauce\EventSourcing\Serialization\SerializablePayload;

final class CheckMate implements SerializablePayload
{
    public function __construct(
        public readonly Color $winningColor,
        public readonly Color $losingColor,
    ) {
    }

    public function toPayload(): array
    {
        return [
            'winning_color' => $this->winningColor->value,
            'losing_color' => $this->losingColor->value,
        ];
    }

    public static function fromPayload(array $payload): static
    {
        return new self(
            Color::from($payload['winning_color']),
            Color::from($payload['losing_color']),
        );
    }
}
