<?php

declare(strict_types=1);

namespace Domain\ChessGame\Infrastructure\Read;

use Domain\ChessGame\Application\Read\IAggregateGameQueries;
use Domain\ChessGame\Application\Read\Models\AggregateGame;
use Domain\ChessGame\Domain\ValueObject\GameAggregateId;
use Illuminate\Support\Facades\DB;

final class AggregateGameQueries implements IAggregateGameQueries
{
    public function getEventsByAggregateId(GameAggregateId $aggregateId): array
    {
        $result = DB::table('aggregate_game')
            ->select([
                DB::raw('BIN_TO_UUID(event_id) as event_id'),
                DB::raw('BIN_TO_UUID(aggregate_root_id) as aggregate_root_id'),
                'version',
                'payload',
            ])
            ->whereRaw('aggregate_root_id = UUID_TO_BIN(?)', [$aggregateId->value])
            ->get()
            ->map(static fn (object $result): AggregateGame => new AggregateGame(
                $result->event_id,
                $result->aggregate_root_id,
                $result->version,
                $result->payload,
            ));

        return $result->all();
    }
}
