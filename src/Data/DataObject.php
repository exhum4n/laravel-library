<?php

declare(strict_types=1);

namespace Exhum4n\LaravelLibrary\Data;

abstract class DataObject
{
    public function __construct(?array $values = [])
    {
        if (is_null($values)) {
            return;
        }

        foreach ($values as $key => $value) {
            $key = lcfirst($key);

            if (property_exists(static::class, $key) && $value !== null) {
                $this->$key = $value;
            }
        }
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
