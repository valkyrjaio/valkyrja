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

namespace Valkyrja\Cache\Manager;

use Override;
use Predis\Client;
use Valkyrja\Cache\Manager\Contract\CacheContract as Contract;
use Valkyrja\Cache\Tagger\Contract\TaggerContract;
use Valkyrja\Cache\Tagger\Tagger as TagClass;

class RedisCache implements Contract
{
    public function __construct(
        protected Client $client,
        protected string $prefix = ''
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function has(string $key): bool
    {
        return (bool) $this->client->exists($this->getKey($key));
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function get(string $key): string|null
    {
        return $this->client->get($this->getKey($key));
    }

    /**
     * @inheritDoc
     *
     * @psalm-suppress MixedReturnTypeCoercion
     */
    #[Override]
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
    #[Override]
    public function put(string $key, string $value, int $minutes): void
    {
        $this->client->setex($this->getKey($key), $minutes * 60, $value);
    }

    /**
     * @inheritDoc
     */
    #[Override]
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
    #[Override]
    public function increment(string $key, int $value = 1): int
    {
        return $this->client->incrby($this->getKey($key), $value);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function decrement(string $key, int $value = 1): int
    {
        return $this->client->decrby($this->getKey($key), $value);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function forever(string $key, $value): void
    {
        $this->client->set($this->getKey($key), $value);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function forget(string $key): bool
    {
        return (bool) $this->client->del([$this->getKey($key)]);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function flush(): bool
    {
        return (bool) $this->client->flushdb();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getPrefix(): string
    {
        return $this->prefix;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getTagger(string ...$tags): TaggerContract
    {
        return TagClass::make($this, ...$tags);
    }

    /**
     * Get key.
     *
     *
     */
    protected function getKey(string $key): string
    {
        return $this->getPrefix() . $key;
    }
}
