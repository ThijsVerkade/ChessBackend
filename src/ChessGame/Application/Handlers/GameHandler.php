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
use Domain\ChessGame\Domain\ValueObject\Position;
use EventSauce\EventSourcing\AggregateRootRepository;
use Exception;

class GameHandler implements IGameHandler
{
    public function __construct(
        private readonly AggregateRootRepository $aggregateRootRepository
    ) {
    }

    #[\Override]
    public function handleStartGame(StartGameCommand $startGameCommand): ChessGame
    {
        $chessGameAggregate = ChessGame::startGame(
            $startGameCommand->gameAggregateId,
        );
        $this->aggregateRootRepository->persist($chessGameAggregate);

        return $chessGameAggregate;
    }

    #[\Override]
    public function handleDrawGame(DrawGameCommand $drawGameCommand): void
    {
        /** @var ChessGame $aggregateRoot */
        $aggregateRoot = $this->aggregateRootRepository->retrieve($drawGameCommand->gameAggregateId);

        $this->aggregateRootRepository->persist($aggregateRoot);
    }

    #[\Override]
    public function handleResignGame(ResignGameCommand $resignGameCommand): void
    {
        /** @var ChessGame $aggregateRoot */
        $aggregateRoot = $this->aggregateRootRepository->retrieve($resignGameCommand->gameAggregateId);
        $aggregateRoot->resign();
    }

    #[\Override]
    public function handleMovePiece(MovePieceCommand $movePieceCommand): ChessGame
    {
        /** @var ChessGame $aggregateRoot */
        $aggregateRoot = $this->aggregateRootRepository->retrieve($movePieceCommand->gameAggregateId);

        $aggregateRoot->movePiece($movePieceCommand->startPosition, $movePieceCommand->endPosition);

        $this->aggregateRootRepository->persist($aggregateRoot);

        return $aggregateRoot;
    }

    #[\Override]
    public function handleOpponentMove(Commands\OpponentMoveCommand $opponentMoveCommand): ChessGame
    {
        /** @var ChessGame $aggregateRoot */
        $aggregateRoot = $this->aggregateRootRepository->retrieve($opponentMoveCommand->gameAggregateId);

        $aggregateRoot->generateRandomBotMove();

        $this->aggregateRootRepository->persist($aggregateRoot);

        return $aggregateRoot;
    }

    #[\Override]
    public function handleContinueGame(ContinueGameCommand $continueGameCommand): ChessGame
    {
        /** @var ChessGame $aggregateRoot */
        $aggregateRoot = $this->aggregateRootRepository->retrieve($continueGameCommand->gameAggregateId);

        return $aggregateRoot;
    }

    #[\Override]
    public function handlePromotePawn(Commands\PromotePawnCommand $promotePawnCommand): ChessGame
    {
        /** @var ChessGame $aggregateRoot */
        $aggregateRoot = $this->aggregateRootRepository->retrieve($promotePawnCommand->gameAggregateId);

        $aggregateRoot->promotePawn($promotePawnCommand->position, $promotePawnCommand->pieceType);

        return $aggregateRoot;
    }

    /**
     * @return Position[]
     */
    #[\Override]
    public function handleGetAvailablePositions(
        Commands\GetAvailablePositionsCommand $getAvailablePositionsCommand
    ): array {
        /** @var ChessGame $aggregateRoot */
        $aggregateRoot = $this->aggregateRootRepository->retrieve($getAvailablePositionsCommand->gameAggregateId);

        $square = $aggregateRoot->board->getSquareByPosition($getAvailablePositionsCommand->position);

        if ($square->piece === null) {
            throw new Exception('No piece available for this position');
        }

        return $square->piece->availableMoves($getAvailablePositionsCommand->position, $aggregateRoot->board);
    }
}
