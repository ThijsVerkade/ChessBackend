<?php

declare(strict_types=1);

namespace Src\ChessGame\Domain\Events;

use Src\ChessGame\Domain\Enum\Color;
use Src\ChessGame\Domain\Enum\PieceType;
use Src\ChessGame\Domain\ValueObject\Position;
use EventSauce\EventSourcing\Serialization\SerializablePayload;

final readonly class PromotePawnRequired implements SerializablePayload
{
    public function __construct(
        public Position $position,
        public Color $color,
    ) {
    }

    #[\Override]
    public function toPayload(): array
    {
        return [
            'position' => $this->position->toString(),
            'color' => $this->color->value,
        ];
    }

    #[\Override]
    public static function fromPayload(array $payload): static
    {
        return new self(
            Position::fromString($payload['position']),
            Color::from($payload['color']),
        );
    }
}
