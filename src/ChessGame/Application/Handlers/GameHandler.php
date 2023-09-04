<?php

declare(strict_types=1);

namespace Domain\ChessGame\Application\Handlers;

use Domain\ChessGame\Application\Commands;
use Domain\ChessGame\Application\Commands\ContinueGameCommand;
use Domain\ChessGame\Application\Commands\DrawGameCommand;
use Domain\ChessGame\Application\Commands\MovePieceCommand;
use Domain\ChessGame\Application\Commands\ResignGameCommand;
use Domain\ChessGame\Application\Commands\StartGameCommand;
use Domain\ChessGame\Domain\ChessGame;
use EventSauce\EventSourcing\AggregateRootRepository;

class GameHandler implements IGameHandler
{
    public function __construct(
        private readonly AggregateRootRepository $aggregateRootRepository
    ) {
    }

    public function handleStartGame(StartGameCommand $command): ChessGame
    {
        $chessGameAggregate = ChessGame::startGame($command->gameAggregateId);
        $this->aggregateRootRepository->persist($chessGameAggregate);

        return $chessGameAggregate;
    }

    public function handleDrawGame(DrawGameCommand $command): void
    {
        /** @var ChessGame $chessGameAggregate */
        $chessGameAggregate = $this->aggregateRootRepository->retrieve($command->gameAggregateId);

        $this->aggregateRootRepository->persist($chessGameAggregate);
    }

    public function handleResignGame(ResignGameCommand $command): void
    {
        // TODO: Implement handleResignGame() method.
    }

    public function handleMovePiece(MovePieceCommand $command): ChessGame
    {
        /** @var ChessGame $chessGameAggregate */
        $chessGameAggregate = $this->aggregateRootRepository->retrieve($command->gameAggregateId);

        $chessGameAggregate->movePiece($command->startPosition, $command->endPosition);

        $this->aggregateRootRepository->persist($chessGameAggregate);

        return $chessGameAggregate;
    }

    public function handleContinueGame(ContinueGameCommand $command): ChessGame
    {
        /** @var ChessGame $chessGameAggregate */
        $chessGameAggregate = $this->aggregateRootRepository->retrieve($command->gameAggregateId);

        return $chessGameAggregate;
    }

    public function handlePromotePawn(Commands\PromotePawnCommand $command): ChessGame
    {
        /** @var ChessGame $chessGameAggregate */
        $chessGameAggregate = $this->aggregateRootRepository->retrieve($command->gameAggregateId);

        $chessGameAggregate->promotePawn($command->position, $command->pieceType);

        return $chessGameAggregate;
    }
}
