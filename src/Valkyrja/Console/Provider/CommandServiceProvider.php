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

namespace Valkyrja\Console\Provider;

use Valkyrja\Application\Config\Valkyrja;
use Valkyrja\Cli\Routing\Collection\Contract\Collection as CliRoutingCollection;
use Valkyrja\Console\Command\ClearCache;
use Valkyrja\Console\Command\CommandsList;
use Valkyrja\Console\Command\CommandsListForBash;
use Valkyrja\Console\Command\OptimizeCacheCommand;
use Valkyrja\Console\Contract\Console;
use Valkyrja\Console\Input\Contract\Input;
use Valkyrja\Console\Output\Contract\Output;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Event\Collection\Contract\Collection as EventCollection;
use Valkyrja\Http\Routing\Collection\Contract\Collection as HttpRoutingCollection;

/**
 * Class CommandServiceProvider.
 *
 * @author Melech Mizrachi
 */
final class CommandServiceProvider extends Provider
{
    /**
     * @inheritDoc
     */
    public static function publishers(): array
    {
        return [
            ClearCache::class           => [self::class, 'publishClearCache'],
            CommandsList::class         => [self::class, 'publishCommandsList'],
            CommandsListForBash::class  => [self::class, 'publishCommandsListForBash'],
            OptimizeCacheCommand::class => [self::class, 'publishOptimizeCacheCommand'],
        ];
    }

    /**
     * @inheritDoc
     */
    public static function provides(): array
    {
        return [
            ClearCache::class,
            CommandsList::class,
            CommandsListForBash::class,
            OptimizeCacheCommand::class,
        ];
    }

    /**
     * Publish the clear cache command.
     */
    public static function publishClearCache(Container $container): void
    {
        $container->setSingleton(
            ClearCache::class,
            new ClearCache(
                config: $container->getSingleton(Valkyrja::class),
                input: $container->getSingleton(Input::class),
                output: $container->getSingleton(Output::class)
            ),
        );
    }

    /**
     * Publish the commands list command.
     */
    public static function publishCommandsList(Container $container): void
    {
        $container->setSingleton(
            CommandsList::class,
            new CommandsList(
                console: $container->getSingleton(Console::class),
                input: $container->getSingleton(Input::class),
                output: $container->getSingleton(Output::class)
            ),
        );
    }

    /**
     * Publish the commands list for bash command.
     */
    public static function publishCommandsListForBash(Container $container): void
    {
        $container->setSingleton(
            CommandsListForBash::class,
            new CommandsListForBash(
                console: $container->getSingleton(Console::class),
                input: $container->getSingleton(Input::class),
                output: $container->getSingleton(Output::class)
            ),
        );
    }

    /**
     * Publish the optimize cache command.
     */
    public static function publishOptimizeCacheCommand(Container $container): void
    {
        $container->setSingleton(
            OptimizeCacheCommand::class,
            new OptimizeCacheCommand(
                container: $container,
                console: $container->getSingleton(Console::class),
                cli: $container->getSingleton(CliRoutingCollection::class),
                eventCollection: $container->getSingleton(EventCollection::class),
                routerCollection: $container->getSingleton(HttpRoutingCollection::class),
                config: $container->getSingleton(Valkyrja::class),
                input: $container->getSingleton(Input::class),
                output: $container->getSingleton(Output::class)
            ),
        );
    }
}
