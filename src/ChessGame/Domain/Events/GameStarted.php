<?php

declare(strict_types=1);

namespace Src\ChessGame\Domain\Events;

use EventSauce\EventSourcing\Serialization\SerializablePayload;

final class GameStarted implements SerializablePayload
{
    #[\Override]
    public function toPayload(): array
    {
        return [];
    }

    #[\Override]
    public static function fromPayload(array $payload): static
    {
        return new self();
    }
}
