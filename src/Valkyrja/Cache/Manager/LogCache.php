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

use JsonException;
use Override;
use Valkyrja\Cache\Manager\Contract\CacheContract;
use Valkyrja\Cache\Tagger\Contract\TaggerContract;
use Valkyrja\Cache\Tagger\Tagger;
use Valkyrja\Log\Logger\Contract\LoggerContract;
use Valkyrja\Type\BuiltIn\Support\Arr;

class LogCache implements CacheContract
{
    public function __construct(
        protected LoggerContract $logger,
        protected string $prefix = ''
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function has(string $key): bool
    {
        $key = $this->getKey($key);

        $this->logger->info(self::class . " has: $key");

        return true;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function get(string $key): string|null
    {
        $key = $this->getKey($key);

        $this->logger->info(self::class . " get: $key");

        return '';
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    #[Override]
    public function many(string ...$keys): array
    {
        $keysString = Arr::toString($keys);

        $this->logger->info(self::class . " many: $keysString");

        return [];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function put(string $key, string $value, int $seconds): void
    {
        $key = $this->getKey($key);

        $this->logger->info(self::class . " put: $key, value $value, seconds $seconds");
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    #[Override]
    public function putMany(array $values, int $seconds): void
    {
        $valuesString = Arr::toString($values);

        $this->logger->info(self::class . " putMany: $valuesString, seconds $seconds");
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function increment(string $key, int $value = 1): int
    {
        $key = $this->getKey($key);

        $this->logger->info(self::class . " increment: $key, value $value");

        return $value;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function decrement(string $key, int $value = 1): int
    {
        $key = $this->getKey($key);

        $this->logger->info(self::class . " decrement: $key, value $value");

        return $value;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function forever(string $key, string $value): void
    {
        $key = $this->getKey($key);

        $this->logger->info(self::class . " forever: $key, value $value");
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function forget(string $key): bool
    {
        $key = $this->getKey($key);

        $this->logger->info(self::class . " forget: $key");

        return true;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function flush(): bool
    {
        $this->logger->info(self::class . ' flush');

        return true;
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
        return Tagger::make($this, ...$tags);
    }

    /**
     * Get key.
     */
    protected function getKey(string $key): string
    {
        return $this->getPrefix() . $key;
    }
}
