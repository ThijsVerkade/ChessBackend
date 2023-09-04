<?php

declare(strict_types=1);

namespace Domain\ChessGame\Domain\Events;

use Domain\ChessGame\Domain\Enum\Color;
use Domain\ChessGame\Domain\Enum\PieceType;
use Domain\ChessGame\Domain\ValueObject\Position;
use EventSauce\EventSourcing\Serialization\SerializablePayload;

final class PieceMoved implements SerializablePayload
{
    public function __construct(
        public readonly Position $startPosition,
        public readonly Position $endPosition,
        public readonly PieceType $piece,
        public readonly Color $turnBy,
    ) {
    }

    public function toPayload(): array
    {
        return [
            'start_position' => $this->startPosition->toString(),
            'end_position' => $this->endPosition->toString(),
            'piece' => $this->piece->value,
            'turn_by' => $this->turnBy->value,
        ];
    }

    public static function fromPayload(array $payload): static
    {
        return new self(
            Position::fromString($payload['start_position']),
            Position::fromString($payload['end_position']),
            PieceType::from($payload['piece']),
            Color::from($payload['turn_by']),
        );
    }
}
