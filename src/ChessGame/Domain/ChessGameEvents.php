<?php

declare(strict_types=1);

namespace Src\ChessGame\Domain;

use Src\ChessGame\Domain\Enum\Color;

trait ChessGameEvents
{
    public function applyGameStarted(Events\GameStarted $gameStarted): void
    {
        $this->turn = Color::White;
        $this->board = Board::setupBoard();
        $this->moves[] = $this->board->squares;
    }

    public function applyPieceMoved(Events\PieceMoved $pieceMoved): void
    {
        $this->turn = Color::getOppositeColor($pieceMoved->turnBy);
        $this->board->movePiece($pieceMoved->startPosition, $pieceMoved->endPosition);
        $this->board->lastMoveFrom = $pieceMoved->startPosition;
        $this->board->lastMoveTo = $pieceMoved->endPosition;
        $this->moves[] = $this->board->squares;
        $this->check = false;
    }

    public function applyPieceCaptured(Events\PieceCaptured $pieceCaptured): void
    {
        $this->board->removePiece($pieceCaptured->capturedPosition);
        $this->board->removedPiece = null;
    }

    public function applyHasCastled(Events\HasCastled $hasCastled): void
    {
        $this->board->movePiece($hasCastled->startPositionRook, $hasCastled->endPositionRook);
    }

    public function applyCheck(Events\Check $check): void
    {
        $this->check = true;
    }

    public function applyCheckMate(Events\CheckMate $checkMate): void
    {
        $this->checkmate = true;
    }

    public function applyPromotePawnRequired(Events\PromotePawnRequired $promotePawnRequired): void
    {
        $this->promotionPawnRequired = true;
    }

    public function applyPromotePawn(Events\PromotePawn $promotePawn): void
    {
        $this->board->promotePawn($promotePawn->position, $promotePawn->pieceType);
        $this->promotionPawnRequired = false;
    }
}
