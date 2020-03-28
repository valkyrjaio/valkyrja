<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Session\Sessions;

use Valkyrja\Application\Application;
use Valkyrja\Cache\Cache;
use Valkyrja\Cache\Store;
use Valkyrja\Session\Exceptions\SessionStartFailure;

use function headers_sent;
use function json_decode;
use function json_encode;

use function session_start;

use const JSON_THROW_ON_ERROR;

/**
 * Class CacheSession.
 *
 * @author Melech Mizrachi
 */
class CacheSession extends Session
{
    /**
     * The cache.
     *
     * @var Cache
     */
    protected Cache $cache;

    /**
     * The cache store.
     *
     * @var Store
     */
    protected Store $cacheStore;

    /**
     * CacheSession constructor.
     *
     * @param Application $application
     * @param string|null $sessionId
     * @param string|null $sessionName
     */
    public function __construct(Application $application, string $sessionId = null, string $sessionName = null)
    {
        parent::__construct($application, $sessionId, $sessionName);

        $this->cache      = $this->app->cache();
        $this->cacheStore = $this->cache->getStore();
    }

    /**
     * Start the session.
     *
     * @throws SessionStartFailure
     *
     * @return void
     */
    public function start(): void
    {
        // If the session is already active
        if ($this->isActive() || headers_sent()) {
            // No need to reactivate
            return;
        }

        // If the session failed to start
        if (! session_start()) {
            // Throw a new exception
            throw new SessionStartFailure('The session failed to start!');
        }

        // Set the data
        $this->data = json_decode($this->cacheStore->get($this->getCacheSessionId()), true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * Set an item into the session.
     *
     * @param string $id    The id
     * @param string $value The value
     *
     * @return void
     */
    public function set(string $id, string $value): void
    {
        $this->data[$id] = $value;

        $this->updateCacheSession();
    }

    /**
     * Remove a session item.
     *
     * @param string $id The item id
     *
     * @return bool
     */
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
     * Clear the local session.
     *
     * @return void
     */
    public function clear(): void
    {
        parent::clear();

        $this->updateCacheSession();
    }

    /**
     * Destroy the session.
     *
     * @return void
     */
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
     * @return void
     */
    protected function updateCacheSession(): void
    {
        $this->cacheStore->forever($this->getCacheSessionId(), json_encode($this->data, JSON_THROW_ON_ERROR));
    }
}
