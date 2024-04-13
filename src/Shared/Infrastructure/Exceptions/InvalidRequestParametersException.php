<?php

namespace Src\Shared\Infrastructure\Exceptions;

use Src\Shared\Application\ExceptionInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class InvalidRequestParametersException extends \Exception implements ExceptionInterface
{
    /**
     * @var string[]|string[][]|null
     */
    private ?array $options = null;

    /**
     * @var string[]|string[][]|null
     */
    private ?array $fields = null;

    /**
     * @param array<string, string|array<string,string>>|null $options
     * @param array<string>|array<string,string|array<string,string>> $fields
     */
    public static function withContext(
        ?array $options = null,
        array $fields = []
    ): self {
        $exception = new self('Request parameters are invalid');
        $exception->options = $options;
        $exception->fields = $fields;

        return $exception;
    }

    /**
     * @return string[]|string[][]|null
     */
    public function getOptions(): ?array
    {
        return $this->options;
    }

    /**
     * @return string[]|string[][]|null
     */
    public function getFields(): ?array
    {
        return $this->fields;
    }

    #[\Override]
    public function report(): void
    {
        Log::alert(
            $this->message,
            [
                'fields' => $this->fields
            ]
        );
    }

    #[\Override]
    public function render(Request $request): JsonResponse
    {
        if (is_array($this->fields) && $this->fields !== []) {
            $detail = sprintf(
                'Parameters %s were invalid',
                implode(', ', array_keys($this->fields)),
            );
        } else {
            $detail = 'Parameters were invalid';
        }

        return new JsonResponse([
            'type' => 'invalid_request',
            'title' => $this->getMessage(),
            'detail' => $detail,
            'instance' => $request->getUri()
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
