<?php

declare(strict_types=1);

namespace Src\ChessGame\Application\Projectors;

use Src\ChessGame\Domain\Events;
use Src\ChessGame\Domain\Repositories\IGameRepository;
use EventSauce\EventSourcing\EventConsumption\EventConsumer;
use EventSauce\EventSourcing\Message;

final class GameProjection extends EventConsumer
{
    public function __construct(public readonly IGameRepository $gameRepository)
    {
    }

    public function handleGameEndedInDraw(Events\DrawAccepted $drawAccepted, Message $message): void
    {
        $this->gameRepository->drawGame($drawAccepted, $message);
    }

    public function handleGameEndedInResign(Events\GameEndedInResign $gameEndedInResign, Message $message): void
    {
        $this->gameRepository->resignGame($gameEndedInResign, $message);
    }

    public function handleGameWasStarted(Events\GameStarted $gameStarted, Message $message): void
    {
        $this->gameRepository->startGame($gameStarted, $message);
    }

    public function handlePieceWasMoved(Events\PieceMoved $pieceMoved, Message $message): void
    {
        $this->gameRepository->movePiece($pieceMoved, $message);
    }

    public function handleHasCastled(Events\HasCastled $hasCastled, Message $message): void
    {
        $this->gameRepository->castleKing($hasCastled, $message);
    }
}
