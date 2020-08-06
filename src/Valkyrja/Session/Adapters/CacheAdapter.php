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

namespace Valkyrja\Session\Adapters;

use JsonException;
use Valkyrja\Cache\Cache;
use Valkyrja\Cache\Store;
use Valkyrja\Session\Exceptions\SessionStartFailure;
use Valkyrja\Support\Type\Arr;

use function headers_sent;
use function session_start;

/**
 * Class CacheAdapter.
 *
 * @author Melech Mizrachi
 */
class CacheAdapter extends PHPAdapter
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
     * CacheAdapter constructor.
     *
     * @param Cache       $cache       The cache
     * @param array       $config      The config
     * @param string|null $sessionId   [optional] The session id
     * @param string|null $sessionName [optional] The session name
     */
    public function __construct(Cache $cache, array $config, string $sessionId = null, string $sessionName = null)
    {
        parent::__construct($config, $sessionId, $sessionName);

        $this->cache      = $cache;
        $this->cacheStore = $cache->getStore();
    }

    /**
     * Start the session.
     *
     * @throws JsonException
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
        $this->data = Arr::fromString($this->cacheStore->get($this->getCacheSessionId()));
    }

    /**
     * Set an item into the session.
     *
     * @param string $id    The id
     * @param string $value The value
     *
     * @throws JsonException
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
     * @throws JsonException
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
     * @throws JsonException
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
     * @throws JsonException
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
     * @throws JsonException
     *
     * @return void
     */
    protected function updateCacheSession(): void
    {
        $this->cacheStore->forever($this->getCacheSessionId(), Arr::toString($this->data));
    }
}
