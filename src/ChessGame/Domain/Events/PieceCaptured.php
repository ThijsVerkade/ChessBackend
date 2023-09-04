<?php

declare(strict_types=1);

namespace Domain\ChessGame\Domain\Events;

use Domain\ChessGame\Domain\Enum\Color;
use Domain\ChessGame\Domain\Enum\PieceType;
use Domain\ChessGame\Domain\ValueObject\Position;
use EventSauce\EventSourcing\Serialization\SerializablePayload;

final class PieceCaptured implements SerializablePayload
{
    public function __construct(
        public readonly PieceType $capturePiece,
        public readonly Position $capturedPosition,
    ) {
    }

    public function toPayload(): array
    {
        return [
            'capture_piece' => $this->capturePiece->value,
            'capture_position' => $this->capturedPosition->toString(),
        ];
    }

    public static function fromPayload(array $payload): static
    {
        return new self(
            PieceType::from($payload['capture_piece']),
            Position::fromString($payload['capture_position']),
        );
    }
}
