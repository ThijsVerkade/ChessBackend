<?php

declare(strict_types=1);

namespace Domain\ChessGame\Domain\Repositories;

use Domain\ChessGame\Domain\Events;
use EventSauce\EventSourcing\Message;

interface IGameRepository
{
    public function startGame(Events\GameStarted $event, Message $message): void;

    public function movePiece(Events\PieceMoved $event, Message $message): void;

    public function drawGame(Events\DrawAccepted $event, Message $message): void;

    public function resignGame(Events\GameEndedInResign $event, Message $message): void;

    public function castleKing(Events\HasCastled $event, Message $message): void;
}
