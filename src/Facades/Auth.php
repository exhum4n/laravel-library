<?php

/** @noinspection PhpVoidFunctionResultUsedInspection */

declare(strict_types=1);

namespace Exhum4n\LaravelLibrary\Facades;

use Exhum4n\LaravelLibrary\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth as BaseFacade;

class Auth extends BaseFacade
{
    public static function check(): bool
    {
        return auth()->check();
    }

    public static function user(): User|Authenticatable|null
    {
        return auth()->user();
    }

    public static function login(User $user): string
    {
        return auth()->login($user);
    }
}
