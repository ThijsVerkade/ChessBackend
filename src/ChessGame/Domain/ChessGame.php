<?php

declare(strict_types=1);

namespace Src\ChessGame\Domain;

use Src\ChessGame\Application\EngineAdapterInterface;
use Src\ChessGame\Domain\Enum\Color;
use Src\ChessGame\Domain\Enum\PieceType;
use Src\ChessGame\Domain\Events\GameStarted;
use Src\ChessGame\Domain\ValueObject\Player;
use Src\ChessGame\Domain\ValueObject\Position;
use Src\ChessGame\Domain\ValueObject\Square;
use EventSauce\EventSourcing\AggregateRoot;
use EventSauce\EventSourcing\AggregateRootBehaviour;
use Exception;

final class ChessGame implements AggregateRoot
{
    use AggregateRootBehaviour;
    use ChessGameEvents;

    public function __construct(private readonly EngineAdapterInterface $engine)
    {
    }

    public Board $board;

    private Color $turn;

    private bool $check = false;

    private bool $checkmate = false;

    private bool $promotionPawnRequired = false;

    public array $moves = [];

    public Player $playerOne;

    public ?Player $playerTwo = null;


    public static function startGame(
        ValueObject\GameAggregateId $gameAggregateId,
    ): self {
        $game = new self($gameAggregateId);
        $game->board = Board::setupBoard();
        $game->recordThat(new GameStarted());
        $game->playerOne = new Player('John');
        $game->playerTwo = null;

        return $game;
    }

    private function validateStartMove(Square $startSquare): void
    {
        if (is_null($startSquare->piece)) {
            throw new Exception('invalid move piece is null');
        }

        if ($startSquare->piece->color !== $this->turn) {
            throw new Exception('invalid color');
        }
    }

    public function movePiece(Position $startPosition, Position $endPosition): void
    {
        if ($this->checkmate) {
            throw new Exception('game already finished');
        }

        if ($this->promotionPawnRequired) {
            throw new Exception('promote of pawn is required');
        }

        $oppositeColor = Color::getOppositeColor($this->turn);
        $startSquare = $this->board->getSquareByPosition($startPosition);
        $endSquare = $this->board->getSquareByPosition($endPosition);
        $this->validateStartMove($startSquare);

        if (!$startSquare->piece->canMove($startPosition, $endPosition, $this->board)) {
            throw new Exception('invalid move');
        }

        if (!is_null($endSquare->piece)) {
            $this->recordThat(new Events\PieceCaptured($endSquare->piece->type, $endPosition));
            $this->board->removePiece($endPosition);
        } elseif ($startSquare->piece instanceof Piece\Pawn && !is_null($startSquare->piece->takenPiecePosition)) {
            $takenPiece = $this->board->getSquareByPosition($startSquare->piece->takenPiecePosition);
            $this->recordThat(new Events\PieceCaptured(
                $takenPiece->piece->type,
                $startSquare->piece->takenPiecePosition
            ));
            $this->board->removePiece($startSquare->piece->takenPiecePosition);
        } elseif ($startSquare->piece instanceof Piece\King && $startSquare->piece->hasCastled) {
            $rookMove = $this->board->getCastleRookMove($startPosition, $endPosition);
            $this->recordThat(new Events\HasCastled(
                $startPosition,
                $endPosition,
                $rookMove->from,
                $rookMove->to,
                $startSquare->piece->color
            ));
        }

        $this->recordThat(new Events\PieceMoved($startPosition, $endPosition, $startSquare->piece->type, $this->turn));

        if ($this->board->positionNeedsPawnPromotion($endPosition)) {
            $this->promotionPawnRequired = true;
            $this->recordThat(new Events\PromotePawnRequired($endPosition, $this->turn));
        }

        $this->checkIfCheckOrCheckMate($endPosition, $oppositeColor);
    }

    private function checkIfCheckOrCheckMate(
        Position $endPosition,
        Color $oppositeColor
    ): void {
        $endSquare = $this->board->getSquareByPosition($endPosition);

        if ($this->board->colorGivesCheck($oppositeColor)) {
            throw new Exception('invalid move, gives check');
        }

        if ($this->board->pieceGivesCheck($endSquare) || $this->board->colorGivesCheck($this->turn)) {
            $this->recordThat(new Events\Check($oppositeColor, $endSquare->position));
            if (
                !$this->board->checkIfKingCanMove($oppositeColor) &&
                !$this->board->checkIfKingCanBeProtected($endPosition, $oppositeColor)
            ) {
                $this->checkmate = true;
                $this->recordThat(new Events\CheckMate(winningColor: $this->turn, losingColor: $oppositeColor));
            }
        }
    }

    public function generateRandomBotMove(): void
    {
        if ($this->checkmate) {
            return;
        }

        if ($this->check) {
            $availableMoves = $this->board->getAvailableMovesAgainstCheckMate($this->turn);
            $availableMoves[] = $this->board->getMovesToProtectKing($this->board->lastMoveTo, $this->turn);

            $move = $availableMoves[array_rand($availableMoves)];

            $this->movePiece($move->from, $move->to);
        } else {
            $move = $this->board->randomMove($this->turn);

            $this->movePiece($move->from, $move->to);
        }
    }

    public function promotePawn(Position $position, PieceType $pieceType): void
    {
        $piece = $this->board->promotePawn($position, $pieceType);
        $this->promotionPawnRequired = false;
        $this->recordThat(new Events\PromotePawn($position, $piece->color, $pieceType));
    }

    public function resign(): void
    {
        $this->checkmate = true;
        $oppositeColor = Color::getOppositeColor($this->turn);
        $this->recordThat(new Events\CheckMate(winningColor: $oppositeColor, losingColor: $this->turn));
    }
}
