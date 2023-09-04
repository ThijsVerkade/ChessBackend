<?php

declare(strict_types=1);

namespace Domain\ChessGame\Domain\ValueObject;

use Domain\Shared\Domain\ValueObjects\Uuid;
use EventSauce\EventSourcing\AggregateRootId;

class GameAggregateId extends Uuid implements AggregateRootId
{
}
