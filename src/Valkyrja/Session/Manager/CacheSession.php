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

namespace Valkyrja\Session\Manager;

use JsonException;
use Override;
use Valkyrja\Cache\Manager\Contract\CacheContract;
use Valkyrja\Session\Manager\Abstract\Session;
use Valkyrja\Type\Array\Factory\ArrayFactory;

class CacheSession extends Session
{
    /**
     * @param non-empty-string|null $sessionId   The session id
     * @param non-empty-string|null $sessionName The session id
     */
    public function __construct(
        protected CacheContract $cache,
        string|null $sessionId = null,
        string|null $sessionName = null
    ) {
        parent::__construct(
            sessionId: $sessionId,
            sessionName: $sessionName
        );
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    #[Override]
    public function start(): void
    {
        $cachedData = $this->cache->get($this->getCacheSessionId());

        // If the session data isn't present
        if ($cachedData === null || $cachedData === '') {
            return;
        }

        // Set the data
        $this->setDataFromCacheValue($cachedData);
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    #[Override]
    public function set(string $id, $value): void
    {
        parent::set($id, $value);

        $this->updateCacheSession();
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    #[Override]
    public function remove(string $id): bool
    {
        $removed = parent::remove($id);

        if ($removed) {
            $this->updateCacheSession();
        }

        return $removed;
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    #[Override]
    public function clear(): void
    {
        parent::clear();

        $this->updateCacheSession();
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    #[Override]
    public function destroy(): void
    {
        parent::destroy();

        $this->updateCacheSession();
    }

    /**
     * Get the cache session id.
     */
    protected function getCacheSessionId(): string
    {
        return $this->getId() . '_session';
    }

    /**
     * @param non-empty-string $value The cookie value
     *
     * @throws JsonException
     */
    protected function setDataFromCacheValue(string $value): void
    {
        /** @psalm-suppress MixedPropertyTypeCoercion */
        $this->data = ArrayFactory::fromString($value);
    }

    /**
     * @throws JsonException
     */
    protected function getDataAsCacheValue(): string
    {
        return ArrayFactory::toString($this->data);
    }

    /**
     * Update the cache session.
     *
     * @throws JsonException
     */
    protected function updateCacheSession(): void
    {
        $this->cache->forever(
            $this->getCacheSessionId(),
            $this->getDataAsCacheValue()
        );
    }
}
