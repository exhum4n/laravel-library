<?php

declare(strict_types=1);

namespace Exhum4n\LaravelLibrary\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response as Code;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    protected array $allowedHttpCodes = [
        Code::HTTP_UNAUTHORIZED,
        Code::HTTP_FORBIDDEN,
        Code::HTTP_NOT_FOUND,
        Code::HTTP_UNPROCESSABLE_ENTITY,
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
        $exceptionCode = $exception->getCode();
        if (in_array($exception, $this->allowedHttpCodes) === false) {
            return 500;
        }

        return $exceptionCode;
    }

    protected function isValidationException(Throwable $exception): bool
    {
        return $exception instanceof ValidationException;
    }
}
