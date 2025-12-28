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
use Valkyrja\Cache\Contract\Cache;
use Valkyrja\Session\Data\CookieParams;
use Valkyrja\Session\Exception\SessionStartFailure;
use Valkyrja\Type\BuiltIn\Support\Arr;

use function headers_sent;
use function session_start;

/**
 * Class CacheSession.
 *
 * @author Melech Mizrachi
 */
class CacheSession extends PhpSession
{
    /**
     * CacheSession constructor.
     */
    public function __construct(
        protected Cache $cache,
        CookieParams $cookieParams,
        string|null $sessionId = null,
        string|null $sessionName = null
    ) {
        parent::__construct(
            cookieParams: $cookieParams,
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
        // If the session is already active
        if ($this->isActive() || headers_sent()) {
            // No need to reactivate
            return;
        }

        // If the session failed to start
        if (
            ! session_start()
            || ($cachedData = $this->cache->get($this->getCacheSessionId())) === null
            || $cachedData === ''
        ) {
            // Throw a new exception
            throw new SessionStartFailure('The session failed to start!');
        }

        // Set the data
        /** @psalm-suppress MixedPropertyTypeCoercion */
        $this->data = Arr::fromString($cachedData);
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    #[Override]
    public function set(string $id, $value): void
    {
        $this->data[$id] = $value;

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
        if (! $this->has($id)) {
            return false;
        }

        unset($this->data[$id]);

        $this->updateCacheSession();

        return true;
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
     *
     * @return string
     */
    protected function getCacheSessionId(): string
    {
        return $this->getId() . '_session';
    }

    /**
     * Update the cache session.
     *
     * @throws JsonException
     *
     * @return void
     */
    protected function updateCacheSession(): void
    {
        $this->cache->forever($this->getCacheSessionId(), Arr::toString($this->data));
    }
}
