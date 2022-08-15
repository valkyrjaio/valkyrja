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

namespace Valkyrja\Cache\Drivers;

use Valkyrja\Cache\Adapter;
use Valkyrja\Cache\Driver as Contract;
use Valkyrja\Cache\Tagger;
use Valkyrja\Support\Manager\Drivers\Driver as ParentDriver;

/**
 * Class Driver.
 *
 * @author Melech Mizrachi
 *
 * @property Adapter $adapter
 */
class Driver extends ParentDriver implements Contract
{
    /**
     * Driver constructor.
     *
     * @param Adapter $adapter The adapter
     */
    public function __construct(Adapter $adapter)
    {
        parent::__construct($adapter);
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
    public function get(string $key): ?string
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
