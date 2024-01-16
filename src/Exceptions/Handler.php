<?php

declare(strict_types=1);

namespace Exhum4n\LaravelLibrary\Exceptions;

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

    /**
     * @param Throwable $e
     *
     * @throws Throwable
     */
    public function report(Throwable $e): void
    {
        parent::report($e);
    }

    public function render($request, Throwable $e): JsonResponse
    {
        $exceptionCode = $this->getExceptionCode($e);
        if ($e instanceof QueryException) {
            $exceptionCode = 400;
        }

        if ($e instanceof AuthenticationException) {
            $exceptionCode = 401;
        }

        $errorBody = [
            'message' => $e->getMessage(),
        ];

        if ($e instanceof NotFoundHttpException) {
            $errorBody = [
                'message' => 'endpoint_not_found',
            ];
        }

        if ($this->isValidationException($e)) {
            $errorBody['message'] = trans('validation.failed');
            $errorBody['errors'] = json_decode($e->getMessage(), true, 512, JSON_THROW_ON_ERROR);
        }

        if (config('app.debug')) {
            $errorBody['trace'] = $e->getTrace();
        }

        return response()->json($errorBody, $exceptionCode);
    }

    protected function getExceptionCode(Throwable $exception): int
    {
        $code = (int) $exception->getCode();

        if (empty($code) || (is_numeric($code) === false)) {
            return Response::HTTP_FORBIDDEN;
        }

        return $code;
    }

    protected function isValidationException(Throwable $exception): bool
    {
        return $exception instanceof ValidationException;
    }
}
