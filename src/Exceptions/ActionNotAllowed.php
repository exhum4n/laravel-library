<?php

namespace Exhum4n\LaravelLibrary\Exceptions;

use Exception;
use Throwable;

class ActionNotAllowed extends Exception
{
    public function __construct(string $message = "", int $code = 403, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
