<?php

declare(strict_types=1);

namespace Src\ChessGame\Application\Repositories;

use EventSauce\EventSourcing\Message;
use Src\ChessGame\Domain\Events;

interface IGameRepository
{
    public function startGame(Events\GameStarted $gameStarted, Message $message): void;

    public function movePiece(Events\PieceMoved $pieceMoved, Message $message): void;

    public function drawGame(Events\DrawAccepted $drawAccepted, Message $message): void;

    public function resignGame(Events\GameEndedInResign $gameEndedInResign, Message $message): void;

    public function castleKing(Events\HasCastled $hasCastled, Message $message): void;
}
