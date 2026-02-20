<?php

declare(strict_types=1);

namespace Exhum4n\LaravelLibrary\Database;

use Illuminate\Database\Migrations\Migration as BaseMigration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

abstract class Migration extends BaseMigration
{
    public function __construct()
    {
        DB::statement('create schema if not exists ' . $this->schema());
    }

    public function table(): string
    {
        return $this->schema() . '.' . $this->name();
    }

    public function down(): void
    {
        Schema::dropIfExists($this->table());
    }

    protected function createTable(callable $blueprint): void
    {
        Schema::create($this->table(), $blueprint);
    }

    abstract protected function name(): string;

    abstract protected function schema(): string;
}
