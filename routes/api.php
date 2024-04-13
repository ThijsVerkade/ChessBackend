<?php

declare(strict_types=1);

use App\Http\Controllers\Game;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => '/v1/game'], static function () {
    Route::post('/start', Game\StartController::class);
    Route::post('/{gameId}/continue', Game\ContinueController::class);
    Route::post('/{gameId}/move-piece', Game\MovePieceController::class);
    Route::get('/{gameId}/opponent-move', Game\GetOpponentMoveController::class);
    Route::post('/{gameId}/resign', Game\ResignController::class);
    Route::post('/{gameId}/draw', Game\DrawController::class);
    Route::get('/{gameId}/moves', Game\GetMovesController::class);
    Route::get('/{gameId}/available-positions', Game\GetAvailablePositionsController::class);
    Route::post('/{gameId}/promote-pawn', Game\PromotePawnController::class);
});
