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

namespace Valkyrja\Event\Collection;

use Valkyrja\Config\Config;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Event\Attribute\Contract\Attributes;
use Valkyrja\Event\Config as EventConfig;
use Valkyrja\Event\Config\Cache;
use Valkyrja\Support\Cacheable\Cacheable;

/**
 * Class CacheableCollection.
 *
 * @author Melech Mizrachi
 */
class CacheableCollection extends Collection
{
    /**
     * @use Cacheable<EventConfig, Cache>
     */
    use Cacheable;

    /**
     * CacheableCollection constructor.
     *
     * @param Container                        $container
     * @param EventConfig|array<string, mixed> $config
     */
    public function __construct(
        protected Container $container,
        protected EventConfig|array $config
    ) {
    }

    /**
     * @inheritDoc
     *
     * @return Cache
     */
    public function getCacheable(): Config
    {
        $this->setup(true, false);

        $config            = new Cache();
        $config->events    = $this->events;
        $config->listeners = [];

        foreach ($this->listeners as $id => $listener) {
            $config->listeners[$id] = $listener->asArray();
        }

        return $config;
    }

    /**
     * @inheritDoc
     *
     * @return EventConfig|array<string, mixed>
     */
    protected function getConfig(): Config|array
    {
        return $this->config;
    }

    /**
     * @inheritDoc
     *
     * @param EventConfig|array<string, mixed> $config
     */
    protected function beforeSetup(Config|array $config): void
    {
    }

    /**
     * @inheritDoc
     *
     * @param EventConfig|array<string, mixed> $config
     */
    protected function setupNotCached(Config|array $config): void
    {
        $this->events = [];
    }

    /**
     * @inheritDoc
     *
     * @param EventConfig|array<string, mixed> $config
     */
    protected function setupFromCache(Config|array $config): void
    {
        $cache = $config['cache'] ?? require $config['cacheFilePath'];

        $this->events    = $cache['events'];
        $this->listeners = $cache['listeners'];
    }

    /**
     * @inheritDoc
     *
     * @param EventConfig|array<string, mixed> $config
     */
    protected function setupAttributes(Config|array $config): void
    {
        /** @var Attributes $listenerAttributes */
        $listenerAttributes = $this->container->getSingleton(Attributes::class);

        // Get all the annotated listeners from the list of classes
        // Iterate through the listeners
        foreach ($listenerAttributes->getListeners(...$config['listeners']) as $listener) {
            // Set the route
            $this->addListener($listener);
        }
    }

    /**
     * @inheritDoc
     *w
     *
     * @param EventConfig|array<string, mixed> $config
     */
    protected function afterSetup(Config|array $config): void
    {
    }

    /**
     * @inheritDoc
     *
     * @param EventConfig|array<string, mixed> $config
     */
    protected function requireFilePath(Config|array $config): void
    {
        $collection = $this;

        require $config['filePath'];
    }
}
