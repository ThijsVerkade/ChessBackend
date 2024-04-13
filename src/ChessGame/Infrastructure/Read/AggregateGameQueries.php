<?php

declare(strict_types=1);

namespace Src\ChessGame\Infrastructure\Read;

use Src\ChessGame\Application\Read\IAggregateGameQueries;
use Src\ChessGame\Application\Read\Models\AggregateGame;
use Src\ChessGame\Domain\ValueObject\GameAggregateId;
use Illuminate\Support\Facades\DB;

final class AggregateGameQueries implements IAggregateGameQueries
{
    #[\Override]
    public function getEventsByAggregateId(GameAggregateId $gameAggregateId): array
    {
        $result = DB::table('aggregate_game')
            ->select([
                DB::raw('BIN_TO_UUID(event_id) as event_id'),
                DB::raw('BIN_TO_UUID(aggregate_root_id) as aggregate_root_id'),
                'version',
                'payload',
            ])
            ->whereRaw('aggregate_root_id = UUID_TO_BIN(?)', [$gameAggregateId->value])
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
