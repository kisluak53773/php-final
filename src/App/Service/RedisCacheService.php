<?php

declare(strict_types=1);

namespace App\Service;

use Psr\SimpleCache\CacheInterface;
use Predis\Client;
use Predis\Response\Status;

class RedisCacheService implements CacheInterface
{
    public function __construct(private readonly Client $client)
    {}

    public function get(string $key, mixed $default = null): mixed
    {
        $value = $this->client->get($key);

        return $value === false ? $default : $value;
    }

    public function set(string $key, mixed $value, \DateInterval|int|null $ttl = 6000): bool
    {
        $status = $this->client->setex($key, (int) $ttl, $value);

        if ($status instanceof Status) {
            return $status->getPayload() === 'OK';
        }

        return false;
    }

    public function delete(string $key): bool
    {
        return $this->client->del($key) === 1;
    }

    public function clear(): bool
    {
        return $this->client->flushdb();
    }

    public function getMultiple(iterable $keys, mixed $default = null): iterable
    {
        return $this->client->mget((array) $keys);
    }

    public function setMultiple(iterable $values, \DateInterval|int|null $ttl = null): bool
    {
        $result = $this->client->mset((array) $values);

        if ($ttl !== null) {
            foreach (array_keys((array) $values) as $key) {
                $this->client->expire($key, (int) $ttl);
            }
        }

        return $result;
    }

    public function deleteMultiple(iterable $keys): bool
    {
        return $this->client->del((array) $keys) === count((array) $keys);
    }

    public function has(string $key): bool
    {
        return $this->client->exists($key) === 1;
    }
}