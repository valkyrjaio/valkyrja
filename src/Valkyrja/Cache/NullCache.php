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
use Valkyrja\Cache\Tagger\Contract\Tagger;
use Valkyrja\Cache\Tagger\Tagger as TagClass;

/**
 * Class NullCache.
 *
 * @author Melech Mizrachi
 */
class NullCache implements Contract
{
    /**
     * NullCache constructor.
     */
    public function __construct(
        protected string $prefix = ''
    ) {
    }

    /**
     * @inheritDoc
     */
    public function has(string $key): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function get(string $key): string|null
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    public function many(string ...$keys): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function put(string $key, string $value, int $minutes): void
    {
    }

    /**
     * @inheritDoc
     */
    public function putMany(array $values, int $minutes): void
    {
    }

    /**
     * @inheritDoc
     */
    public function increment(string $key, int $value = 1): int
    {
        return $value;
    }

    /**
     * @inheritDoc
     */
    public function decrement(string $key, int $value = 1): int
    {
        return $value;
    }

    /**
     * @inheritDoc
     */
    public function forever(string $key, string $value): void
    {
    }

    /**
     * @inheritDoc
     */
    public function forget(string $key): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function flush(): bool
    {
        return true;
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
