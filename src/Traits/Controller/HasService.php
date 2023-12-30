<?php

declare(strict_types=1);

namespace Exhum4n\LaravelLibrary\Traits\Controller;

trait HasService
{
    abstract protected function service(): string;
}
