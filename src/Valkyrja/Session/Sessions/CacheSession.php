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

namespace Valkyrja\Session\Sessions;

use Valkyrja\Cache\Cache;
use Valkyrja\Cache\Store;
use Valkyrja\Container\Container;
use Valkyrja\Session\Exceptions\SessionStartFailure;
use Valkyrja\Session\Session as Contract;

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
     * @param Cache  $cache       The cache
     * @param array  $config      The config
     * @param string $sessionId   [optional] The session id
     * @param string $sessionName [optional] The session name
     *
     */
    public function __construct(Cache $cache, array $config, string $sessionId = null, string $sessionName = null)
    {
        parent::__construct($config, $sessionId, $sessionName);

        $this->cache      = $cache;
        $this->cacheStore = $cache->getStore();
    }

    /**
     * Publish the provider.
     *
     * @param Container $container
     *
     * @return void
     */
    public static function publish(Container $container): void
    {
        $config = $container->getSingleton('config');

        $container->setSingleton(
            Contract::class,
            new static(
                $container->getSingleton(Cache::class),
                (array) $config['session']
            )
        );
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
