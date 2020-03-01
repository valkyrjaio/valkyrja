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

namespace Valkyrja\Event\Cacheables;

use Valkyrja\Application\Application;
use Valkyrja\Config\Configs\EventConfig;
use Valkyrja\Event\Annotation\ListenerAnnotator;
use Valkyrja\Event\Listener;
use Valkyrja\Support\Cacheables\Cacheable;

/**
 * Trait CacheableEvents.
 *
 * @author Melech Mizrachi
 *
 * @property Application $app
 */
trait CacheableEvents
{
    use Cacheable;

    /**
     * The event listeners.
     *
     * @var Listener[][]
     */
    protected static array $events = [];

    /**
     * Get the config.
     *
     * @return EventConfig|object
     */
    protected function getConfig(): object
    {
        return $this->app->config()->event;
    }

    /**
     * Set not cached.
     *
     * @return void
     */
    protected function setupNotCached(): void
    {
        self::$events = [];
    }

    /**
     * Setup the events from cache.
     *
     * @param EventConfig|object $config
     *
     * @return void
     */
    protected function setupFromCache(object $config): void
    {
        /** @var CacheConfig $cache */
        $cache = $config->cache ?? require $config->cacheFilePath;

        self::$events = unserialize(
            base64_decode($cache->events, true),
            [
                'allowed_classes' => [
                    Listener::class,
                ],
            ]
        );
    }

    /**
     * Setup annotations.
     *
     * @param EventConfig|object $config
     *
     * @return void
     */
    protected function setupAnnotations(object $config): void
    {
        /** @var ListenerAnnotator $containerAnnotations */
        $containerAnnotations = $this->app->container()->getSingleton(ListenerAnnotator::class);

        // Get all the annotated listeners from the list of classes
        // Iterate through the listeners
        foreach ($containerAnnotations->getListeners(...$config->listeners) as $listener) {
            // Set the service
            $this->listen($listener->getEvent(), $listener);
        }
    }

    /**
     * Get a cacheable representation of the events.
     *
     * @return CacheConfig|object
     */
    public function getCacheable(): object
    {
        $this->setup(true, false);

        $config         = new CacheConfig();
        $config->events = base64_encode(serialize(self::$events));

        return $config;
    }

    /**
     * Add an event listener.
     *
     * @param string   $event    The event
     * @param Listener $listener The event listener
     *
     * @return void
     */
    abstract public function listen(string $event, Listener $listener): void;
}
