<?php

/** @noinspection PhpUndefinedMethodInspection */

if (function_exists('enum_to_array') === false) {
    function enum_to_array(string $enum): array
    {
        if (method_exists($enum, 'cases') === false) {
            throw new TypeError();
        }

        $values = [];

        foreach ($enum::cases() as $case) {
            $values[] = $case->name;
        }

        return $values;
    }
}
