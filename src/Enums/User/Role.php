<?php

declare(strict_types=1);

namespace Exhum4n\LaravelLibrary\Enums\User;

enum Role
{
    case administrator;
    case moderator;
    case gamer;
}
