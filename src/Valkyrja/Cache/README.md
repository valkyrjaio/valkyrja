# Cache

## Introduction

The Cache component provides a key-value store with TTL support, tag-based
invalidation, and pluggable backends. The default backend is Redis via Predis.
Log and null implementations are included for testing and development.

## The Cache Contract

`Valkyrja\Cache\Contract\CacheContract` defines the full caching API:

```php
public function has(string $key): bool;
public function get(string $key): string;
public function many(string ...$keys): array;          // returns array<string, string>
public function put(string $key, string $value, int $seconds): void;
public function putMany(array $values, int $seconds): void;
public function increment(string $key, int $value = 1): int;
public function decrement(string $key, int $value = 1): int;
public function forever(string $key, string $value): void;
public function forget(string $key): bool;
public function flush(): bool;
public function getPrefix(): string;
public function getTagger(string ...$tags): TaggerContract;
```

## Tag-Based Invalidation

`getTagger(string ...$tags)` returns a `TaggerContract` that scopes all cache
operations to the given tags, allowing a group of related keys to be flushed
together:

```php
$tagger = $cache->getTagger('users', 'profiles');

$tagger->put('user:1', $userData, 3600);
$tagger->put('user:2', $userData2, 3600);

// Later — invalidate everything tagged 'users':
$tagger->flush();
```

`TaggerContract` exposes the same read/write/delete methods as `CacheContract`,
plus tag management:

```php
public function tag(string $key): static;
public function untag(string $key): static;
public function tagMany(string ...$keys): static;
public function untagMany(string ...$keys): static;
public function getTags(): array;
```

## Implementations

| Class        | Description                                            |
|:-------------|:-------------------------------------------------------|
| `RedisCache` | Redis backend via Predis                               |
| `LogCache`   | Logs all cache operations; useful for debugging        |
| `NullCache`  | No-op; all reads return empty, writes succeed silently |

The active implementation is resolved from the container as `CacheContract`.
Configure the default via your `Env` class.

## Configuration

| Env Constant         | Default                 | Description                             |
|:---------------------|:------------------------|:----------------------------------------|
| `CACHE_DEFAULT`      | `RedisCache::class`     | Implementation bound to `CacheContract` |
| `CACHE_REDIS_PREFIX` | `''`                    | Key prefix for Redis                    |
| `CACHE_REDIS_HOST`   | `'127.0.0.1'`           | Redis host                              |
| `CACHE_REDIS_PORT`   | `6379`                  | Redis port                              |
| `CACHE_LOG_PREFIX`   | `''`                    | Key prefix for the log cache            |
| `CACHE_LOG_LOGGER`   | `LoggerContract::class` | Logger used by `LogCache`               |
| `CACHE_NULL_PREFIX`  | `''`                    | Key prefix for the null cache           |

## Service Registration

The Cache service provider registers the following singletons:

| Contract / Class | Description                          |
|:-----------------|:-------------------------------------|
| `CacheContract`  | Active cache (default: `RedisCache`) |
| `RedisCache`     | Redis implementation                 |
| `LogCache`       | Log implementation                   |
| `NullCache`      | No-op implementation                 |
| `Predis\Client`  | Configured Predis Redis client       |
