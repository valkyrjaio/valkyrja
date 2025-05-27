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

use Valkyrja\Cache\Contract\Cache as Contract;
use Valkyrja\Cache\Driver\Contract\Driver;
use Valkyrja\Cache\Factory\Contract\Factory;
use Valkyrja\Cache\Tagger\Contract\Tagger;
use Valkyrja\Exception\InvalidArgumentException;

/**
 * Class Cache.
 *
 * @author Melech Mizrachi
 */
class Cache implements Contract
{
    /**
     * Cache constructor.
     */
    public function __construct(
        protected Factory $factory,
        protected Config $config
    ) {
    }

    /**
     * @inheritDoc
     */
    public function use(string|null $name = null): Driver
    {
        // The configuration name to use
        $name ??= $this->config->defaultConfiguration;
        // The config to use
        $config = $this->config->configurations->$name
            ?? throw new InvalidArgumentException("$name is not a valid configuration");
        // The driver to use
        $driverClass = $config->driverClass;
        // The adapter to use
        $adapterClass = $config->adapterClass;
        // The cache key to use
        $cacheKey = $name . $adapterClass;

        return $this->drivers[$cacheKey]
            ?? $this->factory->createDriver($driverClass, $adapterClass, $config);
    }

    /**
     * @inheritDoc
     */
    public function has(string $key): bool
    {
        return $this->use()->has($key);
    }

    /**
     * @inheritDoc
     */
    public function get(string $key): string|null
    {
        return $this->use()->get($key);
    }

    /**
     * @inheritDoc
     */
    public function many(string ...$keys): array
    {
        return $this->use()->many(...$keys);
    }

    /**
     * @inheritDoc
     */
    public function put(string $key, string $value, int $minutes): void
    {
        $this->use()->put($key, $value, $minutes);
    }

    /**
     * @inheritDoc
     */
    public function putMany(array $values, int $minutes): void
    {
        $this->use()->putMany($values, $minutes);
    }

    /**
     * @inheritDoc
     */
    public function increment(string $key, int $value = 1): int
    {
        return $this->use()->increment($key, $value);
    }

    /**
     * @inheritDoc
     */
    public function decrement(string $key, int $value = 1): int
    {
        return $this->use()->decrement($key, $value);
    }

    /**
     * @inheritDoc
     */
    public function forever(string $key, string $value): void
    {
        $this->use()->forever($key, $value);
    }

    /**
     * @inheritDoc
     */
    public function forget(string $key): bool
    {
        return $this->use()->forget($key);
    }

    /**
     * @inheritDoc
     */
    public function flush(): bool
    {
        return $this->use()->flush();
    }

    /**
     * @inheritDoc
     */
    public function getPrefix(): string
    {
        return $this->use()->getPrefix();
    }

    /**
     * @inheritDoc
     */
    public function getTagger(string ...$tags): Tagger
    {
        return $this->use()->getTagger(...$tags);
    }
}
