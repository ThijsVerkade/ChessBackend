<?php

use App\Http\Controllers\Api\V1\Game;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('game')
    ->group(static function () {
        Route::post('/start', Game\StartGameController::class);
        Route::post('/{gameId}/continue', Game\ContinueGameController::class);
        Route::post('/{gameId}/move-piece', Game\MovePieceController::class);
        Route::put('/{gameId}/resign', Game\StartGameController::class);
        Route::put('/{gameId}/draw', Game\StartGameController::class);
        Route::get('/{gameId}/events', Game\RetrieveEventsController::class);
        Route::put('/{gameId}/promote-pawn', Game\PromotePawnController::class);
    });
