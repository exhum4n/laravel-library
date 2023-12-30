<?php

/** @noinspection PhpMissingReturnTypeInspection */

declare(strict_types=1);

namespace Exhum4n\LaravelLibrary\Models;

use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Support\Facades\DB;

/**
 * @property int|string $id
 */
abstract class Model extends BaseModel
{
    public function save(array $options = [])
    {
        return DB::transaction(function () use ($options) {
            return parent::save($options);
        });
    }

    public function update(array $attributes = [], array $options = [])
    {
        return DB::transaction(function () use ($attributes, $options) {
            return parent::update($attributes, $options);
        });
    }

    public function delete()
    {
        return DB::transaction(function () {
            return parent::delete();
        });
    }
}
