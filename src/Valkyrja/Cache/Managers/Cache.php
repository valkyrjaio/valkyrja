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

namespace Valkyrja\Cache\Managers;

use Valkyrja\Cache\Cache as Contract;
use Valkyrja\Cache\Config\Config;
use Valkyrja\Cache\Driver;
use Valkyrja\Cache\Factory;
use Valkyrja\Cache\Tagger;
use Valkyrja\Manager\Managers\Manager;

/**
 * Class Cache.
 *
 * @author Melech Mizrachi
 *
 * @extends Manager<Driver, Factory>
 *
 * @property Factory $factory
 */
class Cache extends Manager implements Contract
{
    /**
     * Cache constructor.
     *
     * @param Factory      $factory The factory
     * @param Config|array $config  The config
     */
    public function __construct(Factory $factory, Config|array $config)
    {
        parent::__construct($factory, $config);

        $this->configurations = $config['stores'];
    }

    /**
     * @inheritDoc
     */
    public function use(string|null $name = null): Driver
    {
        /** @var Driver $driver */
        $driver = parent::use($name);

        return $driver;
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
