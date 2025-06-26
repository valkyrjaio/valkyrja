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

namespace Valkyrja\Application\Command;

use Valkyrja\Application\Config\Valkyrja;
use Valkyrja\Cli\Interaction\Enum\ExitCode;
use Valkyrja\Cli\Interaction\Factory\Contract\OutputFactory;
use Valkyrja\Cli\Interaction\Message\Banner;
use Valkyrja\Cli\Interaction\Message\ErrorMessage;
use Valkyrja\Cli\Interaction\Message\Message;
use Valkyrja\Cli\Interaction\Message\SuccessMessage;
use Valkyrja\Cli\Interaction\Output\Contract\Output;
use Valkyrja\Cli\Routing\Attribute\Command as CommandAttribute;
use Valkyrja\Cli\Routing\Collection\CacheableCollection as CliCollection;
use Valkyrja\Cli\Routing\Collection\Contract\Collection as CliCollectionContract;
use Valkyrja\Container\CacheableContainer;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Event\Collection\CacheableCollection as CacheableEvents;
use Valkyrja\Event\Collection\Contract\Collection as EventCollection;
use Valkyrja\Exception\RuntimeException;
use Valkyrja\Http\Routing\Collection\CacheableCollection as HttpCollection;
use Valkyrja\Http\Routing\Collection\Contract\Collection as HttpCollectionContract;

use const LOCK_EX;

/**
 * Class CacheCommand.
 *
 * @author Melech Mizrachi
 */
class CacheCommand
{
    public const string NAME = 'config:cache';

    #[CommandAttribute(
        name: self::NAME,
        description: 'Cache the config',
        helpText: new Message('A command to cache the config.'),
    )]
    public function run(
        Container $container,
        CliCollectionContract $cli,
        EventCollection $eventCollection,
        HttpCollectionContract $routerCollection,
        Valkyrja $config,
        OutputFactory $outputFactory
    ): Output {
        $config = clone $config;

        $cacheFilePath = $config->config->cacheFilePath;

        // If the cache file already exists, delete it
        if (is_file($cacheFilePath)) {
            unlink($cacheFilePath);
        }

        $config->app->debug = false;
        $config->app->env   = 'production';

        /** @var CacheableContainer $container */
        $container = clone $container;
        /** @var CliCollection $cli */
        $cli = clone $cli;
        /** @var CacheableEvents $events */
        $events = clone $eventCollection;
        /** @var HttpCollection $collection */
        $collection = clone $routerCollection;

        $containerCache = $container->getCacheable();
        $cliCache       = $cli->getCacheable();
        $eventsCache    = $events->getCacheable();
        $routesCache    = $collection->getCacheable();

        $config->container->providers = $containerCache->providers;
        $config->container->cache     = $containerCache->cache
            ?? throw new RuntimeException('Container Cache should be set');

        $config->cliRouting->cache = $cliCache;

        $config->event->cache = $eventsCache;

        $config->httpRouting->cache = $routesCache;

        // Get the results of the cache attempt
        $result = file_put_contents($cacheFilePath, $config->asSerializedString(), LOCK_EX);

        if ($result === false) {
            return $outputFactory
                ->createOutput(exitCode: ExitCode::ERROR)
                ->withMessages(
                    new Banner(new ErrorMessage('An error occurred while caching the config.'))
                );
        }

        return $outputFactory
            ->createOutput()
            ->withMessages(
                new Banner(new SuccessMessage('Application config cached successfully.'))
            );
    }
}
