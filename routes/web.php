<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $defaultDocumentation = config('l5-swagger.default');
    $route = config(sprintf('l5-swagger.documentations.%s.routes.api', $defaultDocumentation));

    return redirect()->to($route);
});
