<?php

/**
 * @noinspection PhpUndefinedMethodInspection
 * @noinspection PhpUndefinedFieldInspection
 * @noinspection PhpDynamicFieldDeclarationInspection
 */

declare(strict_types=1);

namespace Exhum4n\LaravelLibrary\Services;

use Exhum4n\LaravelLibrary\Traits\Service\HasRepository;

/**
 * @property $repository
 */
abstract class Service
{
    public function __construct()
    {
        $traits = class_uses_recursive(static::class);

        if (in_array(HasRepository::class, $traits, true)) {
            $this->initializeRepository();
        }
    }

    private function initializeRepository(): void
    {
        $this->repository = app(static::repository());
    }
}
