<?php

declare(strict_types=1);

namespace Src\ChessGame\Domain\Events;

use Src\ChessGame\Domain\Enum\Color;
use Src\ChessGame\Domain\ValueObject\Position;
use EventSauce\EventSourcing\Serialization\SerializablePayload;

final readonly class HasCastled implements SerializablePayload
{
    public function __construct(
        public Position $startPositionKing,
        public Position $endPositionKing,
        public Position $startPositionRook,
        public Position $endPositionRook,
        public Color $color
    ) {
    }

    #[\Override]
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

    #[\Override]
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
