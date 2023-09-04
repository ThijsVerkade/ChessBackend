<?php

declare(strict_types=1);

namespace Domain\ChessGame\Domain\Events;

use EventSauce\EventSourcing\Serialization\SerializablePayload;

final class GameStarted implements SerializablePayload
{
    public function toPayload(): array
    {
        return [];
    }

    public static function fromPayload(array $payload): static
    {
        return new self();
    }
}
