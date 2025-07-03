<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * (c) Melech Mizrachi <melechmizrachi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Cache;

use Predis\Client;
use Valkyrja\Cache\Contract\Cache as Contract;
use Valkyrja\Cache\Tagger\Contract\Tagger;
use Valkyrja\Cache\Tagger\Tagger as TagClass;

/**
 * Class RedisCache.
 *
 * @author Melech Mizrachi
 */
class RedisCache implements Contract
{
    /**
     * RedisCache constructor.
     *
     * @param Client $client The predis client
     * @param string $prefix The prefix
     */
    public function __construct(
        protected Client $client,
        protected string $prefix = ''
    ) {
    }

    /**
     * @inheritDoc
     */
    public function has(string $key): bool
    {
        return (bool) $this->client->exists($this->getKey($key));
    }

    /**
     * @inheritDoc
     */
    public function get(string $key): string|null
    {
        return $this->client->get($this->getKey($key));
    }

    /**
     * @inheritDoc
     *
     * @psalm-suppress MixedReturnTypeCoercion
     */
    public function many(string ...$keys): array
    {
        $prefixedKeys = [];

        foreach ($keys as $key) {
            $prefixedKeys[] = $this->getKey($key);
        }

        return $this->client->mget($prefixedKeys);
    }

    /**
     * @inheritDoc
     */
    public function put(string $key, string $value, int $minutes): void
    {
        $this->client->setex($this->getKey($key), $minutes * 60, $value);
    }

    /**
     * @inheritDoc
     */
    public function putMany(array $values, int $minutes): void
    {
        $seconds = $minutes * 60;

        $this->client->transaction(
            function (Client $client) use ($values, $seconds): void {
                foreach ($values as $key => $value) {
                    $client->setex($this->getKey($key), $seconds, $value);
                }
            }
        );
    }

    /**
     * @inheritDoc
     */
    public function increment(string $key, int $value = 1): int
    {
        return $this->client->incrby($this->getKey($key), $value);
    }

    /**
     * @inheritDoc
     */
    public function decrement(string $key, int $value = 1): int
    {
        return $this->client->decrby($this->getKey($key), $value);
    }

    /**
     * @inheritDoc
     */
    public function forever(string $key, $value): void
    {
        $this->client->set($this->getKey($key), $value);
    }

    /**
     * @inheritDoc
     */
    public function forget(string $key): bool
    {
        return (bool) $this->client->del([$this->getKey($key)]);
    }

    /**
     * @inheritDoc
     */
    public function flush(): bool
    {
        return (bool) $this->client->flushdb();
    }

    /**
     * @inheritDoc
     */
    public function getPrefix(): string
    {
        return $this->prefix;
    }

    /**
     * @inheritDoc
     */
    public function getTagger(string ...$tags): Tagger
    {
        return TagClass::make($this, ...$tags);
    }

    /**
     * Get key.
     *
     * @param string $key
     *
     * @return string
     */
    protected function getKey(string $key): string
    {
        return $this->getPrefix() . $key;
    }
}
