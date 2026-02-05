<?php

namespace App\Exceptions;

use App\Helpers\ApiResponse;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    protected function invalidJson($request, ValidationException $exception)
    {
        return ApiResponse::error(
            $exception->errors(),
            $exception->getMessage(),
            422
        );
    }
}
