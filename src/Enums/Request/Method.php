<?php

declare(strict_types=1);

namespace Exhum4n\LaravelLibrary\Enums\Request;

enum Method
{
    case get;
    case post;
    case put;
    case delete;
    case patch;
    case options;
}
