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

use Valkyrja\Application\Application;
use Valkyrja\Container\Container;
use Valkyrja\Dispatcher\Dispatcher;
use Valkyrja\Event\Annotation\ListenerAnnotator;
use Valkyrja\Event\Config\Cache;
use Valkyrja\Event\Config\Config as EventConfig;
use Valkyrja\Support\Cacheables\Cacheable;

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
     * Publish the provider.
     *
     * @param Application $app The application
     *
     * @return void
     */
    public static function publish(Application $app): void
    {
        $events = new static($app->container(), $app->dispatcher(), (array) $app->config()['event']);

        $app->setEvents($events);

        $events->setup();
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
     * @return void
     */
    protected function beforeSetup(): void
    {
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
        /** @var ListenerAnnotator $containerAnnotations */
        $containerAnnotations = $this->container->getSingleton(ListenerAnnotator::class);

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
     * @return void
     */
    protected function afterSetup(): void
    {
    }
}
