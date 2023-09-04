<?php

declare(strict_types=1);

namespace Domain\ChessGame\Application\Projectors;

use Domain\ChessGame\Domain\Events;
use Domain\ChessGame\Domain\Repositories\IGameRepository;
use EventSauce\EventSourcing\EventConsumption\EventConsumer;
use EventSauce\EventSourcing\Message;

final class GameProjection extends EventConsumer
{
    public function __construct(public readonly IGameRepository $gameRepository)
    {
    }

    public function handleGameEndedInDraw(Events\DrawAccepted $event, Message $message): void
    {
        $this->gameRepository->drawGame($event, $message);
    }

    public function handleGameEndedInResign(Events\GameEndedInResign $event, Message $message): void
    {
        $this->gameRepository->resignGame($event, $message);
    }

    public function handleGameWasStarted(Events\GameStarted $event, Message $message): void
    {
        $this->gameRepository->startGame($event, $message);
    }

    public function handlePieceWasMoved(Events\PieceMoved $event, Message $message): void
    {
        $this->gameRepository->movePiece($event, $message);
    }

    public function handleHasCastled(Events\HasCastled $event, Message $message): void
    {
        $this->gameRepository->castleKing($event, $message);
    }
}
