<?php

declare(strict_types=1);

namespace Src\ChessGame\Domain\ValueObject;

use Src\Shared\Domain\ValueObjects\Uuid;
use EventSauce\EventSourcing\AggregateRootId;

class GameAggregateId extends Uuid implements AggregateRootId
{
}
