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

namespace Valkyrja\Console\Command;

use Valkyrja\Application\Config\Valkyrja;
use Valkyrja\Console\CacheableConsole;
use Valkyrja\Console\Commander\Commander;
use Valkyrja\Console\Constant\ExitCode;
use Valkyrja\Console\Contract\Console;
use Valkyrja\Console\Input\Contract\Input as InputContract;
use Valkyrja\Console\Input\Input;
use Valkyrja\Console\Output\Contract\Output as OutputContract;
use Valkyrja\Console\Output\Output;
use Valkyrja\Console\Support\Provides;
use Valkyrja\Container\CacheableContainer;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Event\Collection\CacheableCollection as CacheableEvents;
use Valkyrja\Event\Collection\Contract\Collection as EventCollection;
use Valkyrja\Exception\RuntimeException;
use Valkyrja\Http\Routing\Collection\CacheableCollection;
use Valkyrja\Http\Routing\Collection\Contract\Collection as RouterCollection;

use function file_put_contents;
use function is_file;
use function unlink;

use const LOCK_EX;

/**
 * Class OptimizeCacheCommand.
 *
 * @author Melech Mizrachi
 */
class OptimizeCacheCommand extends Commander
{
    use Provides;

    /**
     * The command.
     */
    public const COMMAND           = 'optimize:cache';
    public const PATH              = self::COMMAND;
    public const SHORT_DESCRIPTION = 'Optimize the application';

    public function __construct(
        protected Container $container,
        protected Console $console,
        protected EventCollection $eventCollection,
        protected RouterCollection $routerCollection,
        protected Valkyrja $config,
        InputContract $input = new Input(),
        OutputContract $output = new Output()
    ) {
        parent::__construct($input, $output);
    }

    /**
     * @inheritDoc
     */
    public function run(): int
    {
        $config = clone $this->config;

        $cacheFilePath = $config->config->cacheFilePath;

        // If the cache file already exists, delete it
        if (is_file($cacheFilePath)) {
            unlink($cacheFilePath);
        }

        $config->app->debug = false;
        $config->app->env   = 'production';

        /** @var CacheableContainer $container */
        $container = $this->container;
        /** @var CacheableConsole $console */
        $console = $this->console;
        /** @var CacheableEvents $events */
        $events = $this->eventCollection;
        /** @var CacheableCollection $collection */
        $collection = $this->routerCollection;

        $containerCache = $container->getCacheable();
        $consoleCache   = $console->getCacheable();
        $eventsCache    = $events->getCacheable();
        $routesCache    = $collection->getCacheable();

        $config->container->providers = $containerCache->providers;
        $config->container->cache     = $containerCache->cache
            ?? throw new RuntimeException('Container Cache should be set');
        $config->container->useCache  = true;

        $config->console->cache          = $consoleCache;
        $config->console->shouldUseCache = true;

        $config->event->cache    = $eventsCache;
        $config->event->useCache = true;

        $config->httpRouting->cache    = $routesCache;
        $config->httpRouting->useCache = true;

        // $containerProvided = $config->container->cache->provided
        //     ?? [];
        //
        // foreach ($config->container->providers as $key => $provider) {
        //     if (in_array($provider, $containerProvided, true)) {
        //         unset($config->container->providers[$key]);
        //     }
        // }

        // Get the results of the cache attempt
        $result = file_put_contents($cacheFilePath, $config->asSerializedString(), LOCK_EX);

        if ($result === false) {
            $this->output->writeMessage('An error occurred while optimizing the application.', true);

            return ExitCode::FAILURE;
        }

        $this->output->writeMessage('Application optimized successfully', true);

        return ExitCode::SUCCESS;
    }
}
