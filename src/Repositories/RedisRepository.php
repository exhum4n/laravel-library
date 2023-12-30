<?php

/**
 * @noinspection PhpPossiblePolymorphicInvocationInspection
 * @noinspection PhpUndefinedMethodInspection
 */

declare(strict_types=1);

namespace Exhum4n\LaravelLibrary\Repositories;

use Illuminate\Redis\Connections\Connection;
use Illuminate\Support\Facades\Redis;

class RedisRepository
{
    protected string $prefix;
    protected Connection $cache;
    protected int $expirationTime = 86400;

    public function __construct()
    {
        $this->cache = Redis::connection();
    }

    public function set(string $key, $value): void
    {
        $this->cache->set("$this->prefix:$key", $value, 'EX', $this->expirationTime);
    }

    public function get(string $key): ?string
    {
        return $this->cache->get("$this->prefix:$key");
    }

    public function ttl(string $key): int
    {
        return $this->cache->ttl("$this->prefix:$key");
    }

    public function delete(string $key): int
    {
        return $this->cache->del("$this->prefix:$key");
    }
}
