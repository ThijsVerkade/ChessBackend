<?php

declare(strict_types=1);

namespace Domain\ChessGame\Infrastructure\Persistence;

use Domain\ChessGame\Domain\Events;
use Domain\ChessGame\Domain\Repositories\IGameRepository;
use EventSauce\EventSourcing\Message;
use Illuminate\Support\Facades\DB;

class GameRepository implements IGameRepository
{
    public function startGame(Events\GameStarted $event, Message $message): void
    {
        DB::table('games')
            ->insert([
                'aggregate_root_id' => $message->aggregateRootId()->toString(),
            ]);
    }

    public function movePiece(Events\PieceMoved $event, Message $message): void
    {
        // TODO: Implement movePiece() method.
    }

    public function drawGame(Events\DrawAccepted $event, Message $message): void
    {
        // TODO: Implement drawGame() method.
    }

    public function resignGame(Events\GameEndedInResign $event, Message $message): void
    {
        // TODO: Implement resignGame() method.
    }

    public function castleKing(Events\HasCastled $event, Message $message): void
    {
        // TODO: Implement resignGame() method.
    }
}
