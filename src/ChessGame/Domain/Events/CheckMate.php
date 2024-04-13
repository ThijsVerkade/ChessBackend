<?php

declare(strict_types=1);

namespace Src\ChessGame\Domain\Events;

use Src\ChessGame\Domain\Enum\Color;
use EventSauce\EventSourcing\Serialization\SerializablePayload;

final readonly class CheckMate implements SerializablePayload
{
    public function __construct(
        public Color $winningColor,
        public Color $losingColor,
    ) {
    }

    #[\Override]
    public function toPayload(): array
    {
        return [
            'winning_color' => $this->winningColor->value,
            'losing_color' => $this->losingColor->value,
        ];
    }

    #[\Override]
    public static function fromPayload(array $payload): static
    {
        return new self(
            Color::from($payload['winning_color']),
            Color::from($payload['losing_color']),
        );
    }
}
