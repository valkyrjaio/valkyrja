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

namespace Valkyrja\Event\Dispatchers;

use Valkyrja\Container\Container;
use Valkyrja\Dispatcher\Dispatcher;
use Valkyrja\Event\Annotation\Annotator;
use Valkyrja\Event\Config\Cache;
use Valkyrja\Event\Config\Config as EventConfig;
use Valkyrja\Support\Cacheable\Cacheable;

/**
 * Class CacheableEvents.
 *
 * @author Melech Mizrachi
 */
class CacheableEvents extends Events
{
    use Cacheable;

    /**
     * The container.
     *
     * @var Container
     */
    protected Container $container;

    /**
     * Events constructor.
     *
     * @param Container  $container  The container
     * @param Dispatcher $dispatcher The dispatcher
     * @param array      $config     The config
     */
    public function __construct(Container $container, Dispatcher $dispatcher, array $config)
    {
        parent::__construct($dispatcher, $config);

        $this->container = $container;
    }

    /**
     * Get a cacheable representation of the events.
     *
     * @return Cache|object
     */
    public function getCacheable(): object
    {
        $this->setup(true, false);

        $config         = new Cache();
        $config->events = self::$events;

        return $config;
    }

    /**
     * Get the config.
     *
     * @return EventConfig|array
     */
    protected function getConfig()
    {
        return $this->config;
    }

    /**
     * Before setup.
     *
     * @param EventConfig|array $config
     *
     * @return void
     */
    protected function beforeSetup($config): void
    {
    }

    /**
     * Set not cached.
     *
     * @param EventConfig|array $config
     *
     * @return void
     */
    protected function setupNotCached($config): void
    {
        self::$events = [];
    }

    /**
     * Setup the events from cache.
     *
     * @param EventConfig|array $config
     *
     * @return void
     */
    protected function setupFromCache(array $config): void
    {
        $cache = $config['cache'] ?? require $config['cacheFilePath'];

        self::$events = $cache['events'];
    }

    /**
     * Setup annotations.
     *
     * @param EventConfig|array $config
     *
     * @return void
     */
    protected function setupAnnotations($config): void
    {
        /** @var Annotator $containerAnnotations */
        $containerAnnotations = $this->container->getSingleton(Annotator::class);

        // Get all the annotated listeners from the list of classes
        // Iterate through the listeners
        foreach ($containerAnnotations->getListeners(...$config['listeners']) as $listener) {
            // Set the service
            $this->listen($listener->getEvent(), $listener);
        }
    }

    /**
     * After setup.
     *
     * @param EventConfig|array $config
     *
     * @return void
     */
    protected function afterSetup($config): void
    {
    }
}
