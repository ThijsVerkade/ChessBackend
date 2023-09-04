<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Game;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

final class ResignGameController extends Controller
{
    public function __invoke(string $gameUuid): JsonResponse
    {
        // TODO: Implement __invoke() method.

        return new JsonResponse();
    }
}
