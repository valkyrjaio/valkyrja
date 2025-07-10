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

use JsonException;
use Override;
use Valkyrja\Cache\Contract\Cache as Contract;
use Valkyrja\Cache\Tagger\Contract\Tagger;
use Valkyrja\Cache\Tagger\Tagger as TagClass;
use Valkyrja\Log\Contract\Logger;
use Valkyrja\Type\BuiltIn\Support\Arr;

/**
 * Class LogCache.
 *
 * @author Melech Mizrachi
 */
class LogCache implements Contract
{
    /**
     * LogCache constructor.
     *
     * @param Logger $logger The logger service
     * @param string $prefix [optional] The prefix
     */
    public function __construct(
        protected Logger $logger,
        protected string $prefix = ''
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function has(string $key): bool
    {
        $this->logger->info(self::class . " has: $key");

        return true;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function get(string $key): string|null
    {
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
    public function put(string $key, string $value, int $minutes): void
    {
        $this->logger->info(self::class . " put: $key, value $value, minutes $minutes");
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    #[Override]
    public function putMany(array $values, int $minutes): void
    {
        $valuesString = Arr::toString($values);

        $this->logger->info(self::class . " putMany: $valuesString, minutes $minutes");
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function increment(string $key, int $value = 1): int
    {
        $this->logger->info(self::class . " increment: $key, value $value");

        return $value;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function decrement(string $key, int $value = 1): int
    {
        $this->logger->info(self::class . " decrement: $key, value $value");

        return $value;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function forever(string $key, $value): void
    {
        $this->logger->info(self::class . " forever: $key, value $value");
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function forget(string $key): bool
    {
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
