<?php

declare(strict_types=1);

namespace Domain\ChessGame\Domain\Events;

use Domain\ChessGame\Domain\Enum\Color;
use Domain\ChessGame\Domain\Enum\PieceType;
use Domain\ChessGame\Domain\ValueObject\Position;
use EventSauce\EventSourcing\Serialization\SerializablePayload;

final class PromotePawn implements SerializablePayload
{
    public function __construct(
        public readonly Position $position,
        public readonly Color $color,
        public readonly PieceType $pieceType,
    ) {
    }

    public function toPayload(): array
    {
        return [
            'position' => $this->position->toString(),
            'color' => $this->color->value,
            'piece_type' => $this->pieceType->value,
        ];
    }

    public static function fromPayload(array $payload): static
    {
        return new self(
            Position::fromString($payload['position']),
            Color::from($payload['color']),
            PieceType::from($payload['piece_type']),
        );
    }
}
