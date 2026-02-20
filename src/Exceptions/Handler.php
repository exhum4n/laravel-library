<?php

declare(strict_types=1);

namespace Exhum4n\LaravelLibrary\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    public function render($request, Throwable $e): JsonResponse
    {
        $errorBody = [
            'error' => $this->getMessage($e),
            'details' => $this->getDetail($e),
        ];

        if (config('app.debug')) {
            $errorBody['trace'] = $e->getTrace();
        }

        return response()->json($errorBody, $this->getCode($e));
    }

    protected function getMessage(Throwable $exception): string
    {
        if ($exception instanceof AuthenticationException) {
            return 'unauthenticated';
        }

        if ($exception instanceof NotFoundHttpException) {
            return 'endpoint_not_found';
        }

        if ($exception instanceof ValidationException) {
            return 'validation_failed';
        }

        $message = $exception->getMessage();
        if (empty($message)) {
            return 'internal_server_error';
        }

        return $message;
    }

    protected function getDetail(Throwable $exception): string|array
    {
        if ($exception instanceof ValidationException) {
            return $exception->errors();
        }

        return 'no_details';
    }

    protected function getCode(Throwable $exception): int
    {
        $code = $exception->getCode();

        if ($exception instanceof AuthorizationException) {
            return Response::HTTP_FORBIDDEN;
        }

        if ($exception instanceof ValidationException) {
            return Response::HTTP_UNPROCESSABLE_ENTITY;
        }

        if ($exception instanceof QueryException) {
            return Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        if ($code === 0) {
            $code = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        if (is_numeric($code) === false) {
            return Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return $code;
    }
}
