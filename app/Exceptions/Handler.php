<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Src\Shared\Infrastructure\Exceptions\InvalidRequestParametersException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    #[\Override]
    public function register(): void
    {
        $this->renderable(static fn(ValidationException $validationException, Request $request) => (
            InvalidRequestParametersException::withContext(fields: $validationException->errors())
        )->render($request));

        if (!App::environment('local')) {
            $this->renderable(static fn(Throwable $throwable, Request $request) => new JsonResponse(
                [
                    "type" => sprintf('https://example.com/probs/%s', Str::kebab($throwable::class)),
                    "title" => 'Something went wrong',
                    "detail" => $throwable->getMessage() ?: 'An exception occurred while processing your request',
                    "instance" => $request->url()
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR,
                [
                    'content-type' => 'application/problem+json',
                ]
            ));
        }
    }
}
