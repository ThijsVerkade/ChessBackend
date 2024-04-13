<?php

declare(strict_types=1);

namespace Src\ChessGame\Application\Handlers;

use Src\ChessGame\Application\Commands;
use Src\ChessGame\Domain\ChessGame;
use Src\ChessGame\Domain\ValueObject\Position;

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
