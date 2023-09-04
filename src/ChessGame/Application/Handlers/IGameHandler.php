<?php

declare(strict_types=1);

namespace Domain\ChessGame\Application\Handlers;

use Domain\ChessGame\Application\Commands;
use Domain\ChessGame\Domain\ChessGame;

interface IGameHandler
{
    public function handleDrawGame(Commands\DrawGameCommand $command): void;

    public function handleResignGame(Commands\ResignGameCommand $command): void;

    public function handleMovePiece(Commands\MovePieceCommand $command): ChessGame;

    public function handleStartGame(Commands\StartGameCommand $command): ChessGame;

    public function handleContinueGame(Commands\ContinueGameCommand $command): ChessGame;

    public function handlePromotePawn(Commands\PromotePawnCommand $command): ChessGame;
}
