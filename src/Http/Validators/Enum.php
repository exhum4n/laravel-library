<?php

/** @noinspection PhpUndefinedMethodInspection */

declare(strict_types=1);

namespace Exhum4n\LaravelLibrary\Http\Validators;

class Enum
{
    public function validate(string $attribute, string $value, array $parameters): bool
    {
        if (enum_exists($parameters[0]) === false) {
            return false;
        }

        foreach ($parameters[0]::cases() as $case) {
            if ($case->name === $value) {
                return true;
            }

            if (isset($case->value) === false) {
                continue;
            }

            if ($case->value === $value) {
                return true;
            }
        }

        return false;
    }
}
