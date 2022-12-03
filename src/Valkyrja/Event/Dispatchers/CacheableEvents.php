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

use Valkyrja\Config\Config;
use Valkyrja\Container\Container;
use Valkyrja\Dispatcher\Dispatcher;
use Valkyrja\Event\Annotator;
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
    /**
     * @use Cacheable<EventConfig, Cache>
     */
    use Cacheable;

    /**
     * Events constructor.
     *
     * @param Container         $container  The container
     * @param Dispatcher        $dispatcher The dispatcher
     * @param EventConfig|array $config     The config
     */
    public function __construct(protected Container $container, Dispatcher $dispatcher, EventConfig|array $config)
    {
        parent::__construct($dispatcher, $config);
    }

    /**
     * @inheritDoc
     */
    public function getCacheable(): Config
    {
        $this->setup(true, false);

        $config         = new Cache();
        $config->events = self::$events;

        return $config;
    }

    /**
     * @inheritDoc
     */
    protected function getConfig(): Config|array
    {
        return $this->config;
    }

    /**
     * @inheritDoc
     */
    protected function beforeSetup(Config|array $config): void
    {
    }

    /**
     * @inheritDoc
     */
    protected function setupNotCached(Config|array $config): void
    {
        self::$events = [];
    }

    /**
     * @inheritDoc
     */
    protected function setupFromCache(Config|array $config): void
    {
        $cache = $config['cache'] ?? require $config['cacheFilePath'];

        self::$events = $cache['events'];
    }

    /**
     * @inheritDoc
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
     * @inheritDoc
     */
    protected function setupAttributes(Config|array $config): void
    {
    }

    /**
     * @inheritDoc
     */
    protected function afterSetup($config): void
    {
    }

    /**
     * @inheritDoc
     */
    protected function requireFilePath(Config|array $config): void
    {
        $events = $this;

        require $config['filePath'];
    }
}
