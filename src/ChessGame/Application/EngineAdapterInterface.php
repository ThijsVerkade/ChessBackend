<?php

declare(strict_types=1);

namespace Src\ChessGame\Application;

interface EngineAdapterInterface
{
    public function setupBoard(): void;

    public function movePiece(): void;
}
