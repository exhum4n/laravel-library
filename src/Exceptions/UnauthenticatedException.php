<?php

declare(strict_types=1);

namespace Exhum4n\LaravelLibrary\Exceptions;

use Exception;
use Throwable;
use Symfony\Component\HttpFoundation\Response as HttpCode;

class UnauthenticatedException extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(__('unauthenticated'), HttpCode::HTTP_UNAUTHORIZED, $previous);
    }
}
