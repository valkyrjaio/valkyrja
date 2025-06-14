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

namespace Valkyrja\Cache\Driver;

use Valkyrja\Cache\Adapter\Contract\Adapter;
use Valkyrja\Cache\Driver\Contract\Driver as Contract;
use Valkyrja\Cache\Tagger\Contract\Tagger;

/**
 * Class Driver.
 *
 * @author Melech Mizrachi
 */
class Driver implements Contract
{
    /**
     * Driver constructor.
     */
    public function __construct(
        protected Adapter $adapter
    ) {
    }

    /**
     * @inheritDoc
     */
    public function has(string $key): bool
    {
        return $this->adapter->has($key);
    }

    /**
     * @inheritDoc
     */
    public function get(string $key): string|null
    {
        return $this->adapter->get($key);
    }

    /**
     * @inheritDoc
     */
    public function many(string ...$keys): array
    {
        return $this->adapter->many(...$keys);
    }

    /**
     * @inheritDoc
     */
    public function put(string $key, string $value, int $minutes): void
    {
        $this->adapter->put($key, $value, $minutes);
    }

    /**
     * @inheritDoc
     */
    public function putMany(array $values, int $minutes): void
    {
        $this->adapter->putMany($values, $minutes);
    }

    /**
     * @inheritDoc
     */
    public function increment(string $key, int $value = 1): int
    {
        return $this->adapter->increment($key, $value);
    }

    /**
     * @inheritDoc
     */
    public function decrement(string $key, int $value = 1): int
    {
        return $this->adapter->decrement($key, $value);
    }

    /**
     * @inheritDoc
     */
    public function forever(string $key, string $value): void
    {
        $this->adapter->forever($key, $value);
    }

    /**
     * @inheritDoc
     */
    public function forget(string $key): bool
    {
        return $this->adapter->forget($key);
    }

    /**
     * @inheritDoc
     */
    public function flush(): bool
    {
        return $this->adapter->flush();
    }

    /**
     * @inheritDoc
     */
    public function getPrefix(): string
    {
        return $this->adapter->getPrefix();
    }

    /**
     * @inheritDoc
     */
    public function getTagger(string ...$tags): Tagger
    {
        return $this->adapter->getTagger(...$tags);
    }
}
