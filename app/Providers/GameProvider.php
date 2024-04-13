<?php

declare(strict_types=1);

namespace App\Providers;

use EventSauce\EventSourcing\AggregateRootRepository;
use EventSauce\EventSourcing\EventSourcedAggregateRootRepository;
use EventSauce\EventSourcing\Serialization\ConstructingMessageSerializer;
use EventSauce\EventSourcing\SynchronousMessageDispatcher;
use EventSauce\MessageRepository\IlluminateMessageRepository\IlluminateUuidV4MessageRepository;
use EventSauce\MessageRepository\TableSchema\DefaultTableSchema;
use EventSauce\UuidEncoding\BinaryUuidEncoder;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Src\ChessGame\Application\Handlers\GameHandler;
use Src\ChessGame\Application\Handlers\IGameHandler;
use Src\ChessGame\Application\Projectors\GameProjection;
use Src\ChessGame\Application\Read\IAggregateGameQueries;
use Src\ChessGame\Application\Repositories\IGameRepository;
use Src\ChessGame\Domain\ChessGame;
use Src\ChessGame\Infrastructure\Persistence\GameRepository;
use Src\ChessGame\Infrastructure\Read\AggregateGameQueries;

class GameProvider extends ServiceProvider
{
    #[\Override]
    public function register()
    {
        $this->app->bind(
            AggregateRootRepository::class,
            static fn(Application $application): EventSourcedAggregateRootRepository =>
            new EventSourcedAggregateRootRepository(
                ChessGame::class,
                new IlluminateUuidV4MessageRepository(
                    connection: DB::connection(),
                    tableName: 'aggregate_game',
                    serializer: new ConstructingMessageSerializer(),
                    tableSchema: new DefaultTableSchema(),
                    uuidEncoder: new BinaryUuidEncoder(),
                ),
                new SynchronousMessageDispatcher(
                    $application->make(GameProjection::class),
                )
            )
        );
        $this->app->bind(IAggregateGameQueries::class, AggregateGameQueries::class);
        $this->app->bind(IGameRepository::class, GameRepository::class);
        $this->app->bind(IGameHandler::class, GameHandler::class);
    }
}
