<?php

declare(strict_types=1);

namespace Src\ChessGame\Infrastructure\Persistence;

use EventSauce\EventSourcing\Message;
use Illuminate\Support\Facades\DB;
use Src\ChessGame\Application\Repositories\IGameRepository;
use Src\ChessGame\Domain\Events;

class GameRepository implements IGameRepository
{
    #[\Override]
    public function startGame(Events\GameStarted $gameStarted, Message $message): void
    {
        DB::table('games')
            ->insert([
                'aggregate_root_id' => $message->aggregateRootId()->toString(),
            ]);
    }

    #[\Override]
    public function movePiece(Events\PieceMoved $pieceMoved, Message $message): void
    {
        // TODO: Implement movePiece() method.
    }

    #[\Override]
    public function drawGame(Events\DrawAccepted $drawAccepted, Message $message): void
    {
        // TODO: Implement drawGame() method.
    }

    #[\Override]
    public function resignGame(Events\GameEndedInResign $gameEndedInResign, Message $message): void
    {
        // TODO: Implement resignGame() method.
    }

    #[\Override]
    public function castleKing(Events\HasCastled $hasCastled, Message $message): void
    {
        // TODO: Implement resignGame() method.
    }
}
