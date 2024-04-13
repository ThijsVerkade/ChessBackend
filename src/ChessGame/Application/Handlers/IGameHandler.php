<?php

declare(strict_types=1);

namespace Domain\ChessGame\Application\Handlers;

use Domain\ChessGame\Application\Commands;
use Domain\ChessGame\Domain\ChessGame;
use Domain\ChessGame\Domain\ValueObject\Position;

interface IGameHandler
{
    public function handleDrawGame(Commands\DrawGameCommand $drawGameCommand): void;

    public function handleResignGame(Commands\ResignGameCommand $resignGameCommand): void;

    public function handleMovePiece(Commands\MovePieceCommand $movePieceCommand): ChessGame;

    public function handleOpponentMove(Commands\OpponentMoveCommand $opponentMoveCommand): ChessGame;

    public function handleStartGame(Commands\StartGameCommand $startGameCommand): ChessGame;

    public function handleContinueGame(Commands\ContinueGameCommand $continueGameCommand): ChessGame;

    public function handlePromotePawn(Commands\PromotePawnCommand $promotePawnCommand): ChessGame;

    /**  @return Position[] */
    public function handleGetAvailablePositions(
        Commands\GetAvailablePositionsCommand $getAvailablePositionsCommand
    ): array;
}
