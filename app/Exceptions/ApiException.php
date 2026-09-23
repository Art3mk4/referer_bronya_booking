<?php

declare(strict_types=1);

namespace App\Exceptions;

use RuntimeException;
use Illuminate\Http\JsonResponse;

class ApiException extends RuntimeException
{
    public static function masterNotResolved(): self
    {
        return new self('Current master is not resolved', 401);
    }

    public static function alreadyAttached(): self
    {
        return new self('Master already attached', 200);
    }

    public static function selfReferal(): self
    {
        return new self('Master can\'t attach self', 422);
    }

    public static function codeNotFound(): self
    {
        return new self('Code not found', 404);
    }

    public function render(): JsonResponse
    {
        return new JsonResponse([
            'error' => $this->getMessage(),
        ], $this->getCode());
    }
}