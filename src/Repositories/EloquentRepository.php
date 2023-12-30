<?php

/**
 * @noinspection PhpUnused
 * @noinspection PhpPossiblePolymorphicInvocationInspection
 */

declare(strict_types=1);

namespace Exhum4n\LaravelLibrary\Repositories;

use Carbon\Carbon;
use Exhum4n\LaravelLibrary\Models\Model;
use Exhum4n\LaravelLibrary\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

abstract class EloquentRepository
{
    protected mixed $model;

    public function __construct()
    {
        $this->model = $this->getModel();
    }

    public function __toString(): string
    {
        return class_basename(static::class);
    }

    public function getQuery(): Builder
    {
        return $this->model::query();
    }

    public function getFirstOrFail(array $where): mixed
    {
        return $this->model::where($where)->firstOrFail();
    }

    public function getFirst(array $where): mixed
    {
        return $this->model::where($where)->first();
    }

    public function getById(int|string $id, string $pKey = 'id'): mixed
    {
        return $this->getFirst([
            $pKey => $id
        ]);
    }

    public function getByIdOrFail(int|string $id, string $pKey = 'id'): mixed
    {
        return $this->getFirstOrFail([
            $pKey => $id
        ]);
    }

    public function getByName(string $name): mixed
    {
        return $this->getFirst([
            'name' => $name
        ]);
    }

    public function getDeletedByIdOrFail(string|int $id): mixed
    {
        $query = $this->model::withTrashed();

        $query->where('id', '=', $id);
        $query->whereNotNull('deleted_at');

        return $query->firstOrFail();
    }

    public function get(array $where): Collection
    {
        return $this->model::where($where)->get();
    }

    public function getAll(): Collection
    {
        return $this->model::all();
    }

    public function getWithPagination(?int $perPage = null, ?array $filters = null): LengthAwarePaginator
    {
        return $this->model::where($filters)->paginate($perPage);
    }

    public function create(array $data): Model|User
    {
        $newRecord = new $this->model();

        $newRecord->fill($data);
        $newRecord->save();

        return $newRecord;
    }

    public function createBulk(array $items): Collection
    {
        return $this->executeTransaction(function () use ($items): Collection {
            $result = new Collection();

            foreach ($items as $item) {
                $result->add($this->create($item));
            }

            return $result;
        });
    }

    public function update(Model|User $model, array $data): Model|User
    {
        if (get_class($model) !== $this->model) {
            throw new ModelNotFoundException('wrong_model_class');
        }

        $model->fill($data);
        $model->save();

        return $model;
    }

    public function deleteByModel(Model $model): void
    {
        $model->delete();
    }

    public function exist(array $where): bool
    {
        return $this->model::where($where)->exists();
    }

    public function firstOrCreate(array $where): mixed
    {
        return $this->model::firstOrCreate($where);
    }

    public function executeTransaction(callable $transaction): mixed
    {
        $this->beginTransaction();

        try {
            $result = $transaction();

            $this->commit();

            return $result;
        } catch (QueryException $exception) {
            $this->rollback();

            throw $exception;
        }
    }

    public function getTimestamp(): Carbon
    {
        $dbTimestamp = DB::select('select LOCALTIMESTAMP')[0]->localtimestamp;

        return Carbon::createFromTimeString($dbTimestamp);
    }

    public function beginTransaction(): void
    {
        DB::beginTransaction();
    }

    public function rollback(): void
    {
        DB::rollBack();
    }

    public function commit(): void
    {
        DB::commit();
    }

    abstract protected function getModel(): string;
}
