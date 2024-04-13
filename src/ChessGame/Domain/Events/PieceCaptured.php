<?php

declare(strict_types=1);

namespace Src\ChessGame\Domain\Events;

use Src\ChessGame\Domain\Enum\Color;
use Src\ChessGame\Domain\Enum\PieceType;
use Src\ChessGame\Domain\ValueObject\Position;
use EventSauce\EventSourcing\Serialization\SerializablePayload;

final readonly class PieceCaptured implements SerializablePayload
{
    public function __construct(
        public PieceType $pieceType,
        public Position $capturedPosition,
    ) {
    }

    #[\Override]
    public function toPayload(): array
    {
        return [
            'capture_piece' => $this->pieceType->value,
            'capture_position' => $this->capturedPosition->toString(),
        ];
    }

    #[\Override]
    public static function fromPayload(array $payload): static
    {
        return new self(
            PieceType::from($payload['capture_piece']),
            Position::fromString($payload['capture_position']),
        );
    }
}
