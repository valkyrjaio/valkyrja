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

use Valkyrja\Application\Env;
use Valkyrja\Config\Config\ValkyrjaDataConfig;
use Valkyrja\Console\CacheableConsole;
use Valkyrja\Console\Commander\Commander;
use Valkyrja\Console\Constant\ExitCode;
use Valkyrja\Console\Support\Provides;
use Valkyrja\Container\CacheableContainer;
use Valkyrja\Event\Collection\CacheableCollection as CacheableEvents;
use Valkyrja\Event\Collection\Contract\Collection;
use Valkyrja\Exception\RuntimeException;
use Valkyrja\Http\Routing\Collection\CacheableCollection;

use function file_put_contents;
use function in_array;
use function is_file;
use function unlink;
use function Valkyrja\console;
use function Valkyrja\container;
use function Valkyrja\output;
use function Valkyrja\router;

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
    public const COMMAND           = 'optimize-cache';
    public const PATH              = self::COMMAND;
    public const SHORT_DESCRIPTION = 'Optimize the application';

    /**
     * @inheritDoc
     */
    public function run(): int
    {
        $config = clone new ValkyrjaDataConfig(env: Env::class);

        $cacheFilePath = $config->config->cacheFilePath;

        // If the cache file already exists, delete it
        if (is_file($cacheFilePath)) {
            unlink($cacheFilePath);
        }

        $config->app->debug = false;
        $config->app->env   = 'production';

        /** @var CacheableContainer $container */
        $container = container();
        /** @var CacheableConsole $console */
        $console = console();
        /** @var CacheableEvents $events */
        $events = container()->getSingleton(Collection::class);
        /** @var CacheableCollection $collection */
        $collection = router()->getCollection();

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

        $containerCacheProviders = $config->container->cache->providers
            ?? [];

        foreach ($config->container->providers as $key => $provider) {
            if (in_array($provider, $containerCacheProviders, true)) {
                unset($config->container->providers[$key]);
            }
        }

        // Get the results of the cache attempt
        $result = file_put_contents($cacheFilePath, $config->asSerializedString(), LOCK_EX);

        if ($result === false) {
            output()->writeMessage('An error occurred while optimizing the application.', true);

            return ExitCode::FAILURE;
        }

        output()->writeMessage('Application optimized successfully', true);

        return ExitCode::SUCCESS;
    }
}
