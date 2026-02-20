<?php

declare(strict_types=1);

namespace Exhum4n\LaravelLibrary\Http\Middleware;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @throws AuthenticationException
     */
    protected function redirectTo(Request $request): ?string
    {
        throw new AuthenticationException('unauthenticated');
    }
}