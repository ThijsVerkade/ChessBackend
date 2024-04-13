<?php

declare(strict_types=1);

namespace Src\ChessGame\Domain\Events;

use Src\ChessGame\Domain\Enum\Color;
use Src\ChessGame\Domain\Enum\PieceType;
use Src\ChessGame\Domain\ValueObject\Position;
use EventSauce\EventSourcing\Serialization\SerializablePayload;

final readonly class PromotePawn implements SerializablePayload
{
    public function __construct(
        public Position $position,
        public Color $color,
        public PieceType $pieceType,
    ) {
    }

    #[\Override]
    public function toPayload(): array
    {
        return [
            'position' => $this->position->toString(),
            'color' => $this->color->value,
            'piece_type' => $this->pieceType->value,
        ];
    }

    #[\Override]
    public static function fromPayload(array $payload): static
    {
        return new self(
            Position::fromString($payload['position']),
            Color::from($payload['color']),
            PieceType::from($payload['piece_type']),
        );
    }
}
