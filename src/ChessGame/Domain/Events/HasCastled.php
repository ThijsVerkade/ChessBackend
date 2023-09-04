<?php

declare(strict_types=1);

namespace Domain\ChessGame\Domain\Events;

use Domain\ChessGame\Domain\Enum\Color;
use Domain\ChessGame\Domain\ValueObject\Position;
use EventSauce\EventSourcing\Serialization\SerializablePayload;

final class HasCastled implements SerializablePayload
{
    public function __construct(
        public readonly Position $startPositionKing,
        public readonly Position $endPositionKing,
        public readonly Position $startPositionRook,
        public readonly Position $endPositionRook,
        public readonly Color $color
    ) {
    }

    public function toPayload(): array
    {
        return [
            'start_position_king' => $this->startPositionKing->toString(),
            'end_position_king' => $this->endPositionKing->toString(),
            'start_position_rook' => $this->startPositionRook->toString(),
            'end_position_rook' => $this->endPositionRook->toString(),
            'color' => $this->color->value,
        ];
    }

    public static function fromPayload(array $payload): static
    {
        return new self(
            Position::fromString($payload['start_position_king']),
            Position::fromString($payload['end_position_king']),
            Position::fromString($payload['start_position_rook']),
            Position::fromString($payload['end_position_rook']),
            Color::from($payload['color']),
        );
    }
}
