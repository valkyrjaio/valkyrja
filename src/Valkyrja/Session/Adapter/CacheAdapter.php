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

namespace Valkyrja\Session\Adapter;

use JsonException;
use Valkyrja\Cache\Driver\Contract\Driver as Cache;
use Valkyrja\Session\Config\CacheConfiguration;
use Valkyrja\Session\Exception\SessionStartFailure;
use Valkyrja\Type\BuiltIn\Support\Arr;

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
     * CacheAdapter constructor.
     */
    public function __construct(
        protected Cache $cache,
        CacheConfiguration $config,
        string|null $sessionId = null,
        string|null $sessionName = null
    ) {
        parent::__construct($config, $sessionId, $sessionName);
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
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
        $this->data = Arr::fromString($cachedData);
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
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
