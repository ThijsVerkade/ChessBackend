<?php

declare(strict_types=1);

namespace Domain\ChessGame\Domain;

use Domain\ChessGame\Domain\Enum\Color;

trait ChessGameEvents
{
    public function applyGameStarted(Events\GameStarted $event): void
    {
        $this->turn = Color::White;
        $this->board = Board::setupBoard();
    }

    public function applyPieceMoved(Events\PieceMoved $event): void
    {
        $this->turn = Color::getOppositeColor($event->turnBy);
        $this->board->movePiece($event->startPosition, $event->endPosition);
        $this->board->lastMoveFrom = $event->startPosition;
        $this->board->lastMoveTo = $event->endPosition;
    }

    public function applyPieceCaptured(Events\PieceCaptured $event): void
    {
        $this->board->removePiece($event->capturedPosition);
        $this->board->removedPiece = null;
    }

    public function applyHasCastled(Events\HasCastled $event): void
    {
        $this->board->movePiece($event->startPositionRook, $event->endPositionRook);
    }

    public function applyCheck(Events\Check $event): void
    {
    }

    public function applyCheckMate(Events\CheckMate $event): void
    {
        $this->checkmate = true;
    }

    public function applyPromotePawnRequired(Events\PromotePawnRequired $event): void
    {
        $this->promotionPawnRequired = true;
    }

    public function applyPromotePawn(Events\PromotePawn $event): void
    {
        $this->board->promotePawn($event->position, $event->pieceType);
        $this->promotionPawnRequired = false;
    }
}
