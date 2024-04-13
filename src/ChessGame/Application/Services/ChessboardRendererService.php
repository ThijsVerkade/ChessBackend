<?php

declare(strict_types=1);

namespace Domain\ChessGame\Application\Services;

use Domain\ChessGame\Domain\ChessGame;
use Domain\ChessGame\Domain\Enum\RenderableFileType;
use Domain\ChessGame\Domain\Repositories\IGameRepository;
use Domain\ChessGame\Domain\ValueObject\GameAggregateId;
use Domain\ChessGame\Domain\ValueObject\Square;
use EventSauce\EventSourcing\AggregateRootRepository;

class ChessboardRendererService
{
    public function __construct(
        private readonly AggregateRootRepository $aggregateRootRepository
    ) {
    }

    public function renderGame(GameAggregateId $gameAggregateId, RenderableFileType $renderableFileType): array
    {
        $aggregateRoot = $this->aggregateRootRepository->retrieve($gameAggregateId);

        return match ($renderableFileType) {
            RenderableFileType::SVG => $this->generateSvgFromGame($aggregateRoot)
        };
    }

    private function generateSvgFromGame(ChessGame $chessGame): array
    {
        $board = [];

        foreach ($chessGame->moves as $moves) {
            $board[] = $this->generateSvgFromBoard();
        }

        return $board;
    }

    private function generateSvgFromBoard(): string
    {
        $svg = '<?xml version="1.0" encoding="UTF-8" standalone="no"?>';
        $svg .= '<svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="400" height="400">';
        //        foreach ($moves as $squares) {
        //            foreach ($squares as $square) {
        //                $square
        //            }
        //        }
        return '';
    }
}