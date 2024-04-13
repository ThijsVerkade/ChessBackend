<?php

namespace Src\Shared\Application;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

interface ExceptionInterface
{
    public function report(): void;

    public function render(Request $request): JsonResponse;
}
