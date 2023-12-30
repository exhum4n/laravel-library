<?php

declare(strict_types=1);

namespace Exhum4n\LaravelLibrary\Traits\Service;

trait HasRepository
{
    abstract protected function repository(): string;
}
