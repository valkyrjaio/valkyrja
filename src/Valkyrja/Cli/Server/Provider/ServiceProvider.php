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

namespace Valkyrja\Cli\Server\Provider;

use Override;
use Valkyrja\Application\Env\Env;
use Valkyrja\Cli\Interaction\Data\Config;
use Valkyrja\Cli\Interaction\Factory\Contract\OutputFactoryContract;
use Valkyrja\Cli\Middleware\Handler\Contract\ExitedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\InputReceivedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\ThrowableCaughtHandlerContract;
use Valkyrja\Cli\Routing\Collection\Collection;
use Valkyrja\Cli\Routing\Constant\OptionName;
use Valkyrja\Cli\Routing\Constant\OptionShortName;
use Valkyrja\Cli\Routing\Data\Contract\RouteContract;
use Valkyrja\Cli\Routing\Dispatcher\Contract\RouterContract;
use Valkyrja\Cli\Server\Command\HelpCommand;
use Valkyrja\Cli\Server\Command\ListBashCommand;
use Valkyrja\Cli\Server\Command\ListCommand;
use Valkyrja\Cli\Server\Command\VersionCommand;
use Valkyrja\Cli\Server\Handler\Contract\InputHandlerContract;
use Valkyrja\Cli\Server\Handler\InputHandler;
use Valkyrja\Cli\Server\Middleware\InputReceived\CheckForHelpOptionsMiddleware;
use Valkyrja\Cli\Server\Middleware\InputReceived\CheckForVersionOptionsMiddleware;
use Valkyrja\Cli\Server\Middleware\InputReceived\CheckGlobalInteractionOptionsMiddleware;
use Valkyrja\Cli\Server\Middleware\ThrowableCaught\LogThrowableCaughtMiddleware;
use Valkyrja\Cli\Server\Middleware\ThrowableCaught\OutputThrowableCaughtMiddleware;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Provider;
use Valkyrja\Log\Logger\Contract\LoggerContract;

final class ServiceProvider extends Provider
{
    /**
     * @inheritDoc
     */
    #[Override]
    public static function publishers(): array
    {
        return [
            InputHandlerContract::class                    => [self::class, 'publishInputHandler'],
            HelpCommand::class                             => [self::class, 'publishHelpCommand'],
            ListBashCommand::class                         => [self::class, 'publishListBashCommand'],
            ListCommand::class                             => [self::class, 'publishListCommand'],
            VersionCommand::class                          => [self::class, 'publishVersionCommand'],
            LogThrowableCaughtMiddleware::class            => [self::class, 'publishLogThrowableCaughtMiddleware'],
            OutputThrowableCaughtMiddleware::class         => [self::class, 'publishOutputThrowableCaughtMiddleware'],
            CheckForHelpOptionsMiddleware::class           => [self::class, 'publishCheckForHelpOptionsMiddleware'],
            CheckForVersionOptionsMiddleware::class        => [self::class, 'publishCheckForVersionOptionsMiddleware'],
            CheckGlobalInteractionOptionsMiddleware::class => [self::class, 'publishCheckGlobalInteractionOptionsMiddleware'],
        ];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function provides(): array
    {
        return [
            InputHandlerContract::class,
            HelpCommand::class,
            ListBashCommand::class,
            ListCommand::class,
            VersionCommand::class,
            LogThrowableCaughtMiddleware::class,
            OutputThrowableCaughtMiddleware::class,
            CheckForHelpOptionsMiddleware::class,
            CheckForVersionOptionsMiddleware::class,
            CheckGlobalInteractionOptionsMiddleware::class,
        ];
    }

    /**
     * Publish the input handler service.
     */
    public static function publishInputHandler(ContainerContract $container): void
    {
        $config = $container->getSingleton(Config::class);

        $container->setSingleton(
            InputHandlerContract::class,
            new InputHandler(
                container: $container,
                router: $container->getSingleton(RouterContract::class),
                inputReceivedHandler: $container->getSingleton(InputReceivedHandlerContract::class),
                throwableCaughtHandler: $container->getSingleton(ThrowableCaughtHandlerContract::class),
                exitedHandler: $container->getSingleton(ExitedHandlerContract::class),
                interactionConfig: $config
            ),
        );
    }

    /**
     * Publish the HelpCommand service.
     */
    public static function publishHelpCommand(ContainerContract $container): void
    {
        $container->setSingleton(
            HelpCommand::class,
            new HelpCommand(
                version: $container->getSingleton(VersionCommand::class),
                route: $container->getSingleton(RouteContract::class),
                collection: $container->getSingleton(Collection::class),
                outputFactory: $container->getSingleton(OutputFactoryContract::class),
            )
        );
    }

    /**
     * Publish the HelpCommand service.
     */
    public static function publishListBashCommand(ContainerContract $container): void
    {
        $container->setSingleton(
            ListBashCommand::class,
            new ListBashCommand(
                route: $container->getSingleton(RouteContract::class),
                collection: $container->getSingleton(Collection::class),
                outputFactory: $container->getSingleton(OutputFactoryContract::class),
            )
        );
    }

    /**
     * Publish the ListCommand service.
     */
    public static function publishListCommand(ContainerContract $container): void
    {
        $container->setSingleton(
            ListCommand::class,
            new ListCommand(
                version: $container->getSingleton(VersionCommand::class),
                route: $container->getSingleton(RouteContract::class),
                collection: $container->getSingleton(Collection::class),
                outputFactory: $container->getSingleton(OutputFactoryContract::class),
            )
        );
    }

    /**
     * Publish the VersionCommand service.
     */
    public static function publishVersionCommand(ContainerContract $container): void
    {
        $container->setSingleton(
            VersionCommand::class,
            new VersionCommand(
                outputFactory: $container->getSingleton(OutputFactoryContract::class),
            )
        );
    }

    /**
     * Publish the LogThrowableCaughtMiddleware service.
     */
    public static function publishLogThrowableCaughtMiddleware(ContainerContract $container): void
    {
        $container->setSingleton(
            LogThrowableCaughtMiddleware::class,
            new LogThrowableCaughtMiddleware(
                logger: $container->getSingleton(LoggerContract::class),
            )
        );
    }

    /**
     * Publish the OutputThrowableCaughtMiddleware service.
     */
    public static function publishOutputThrowableCaughtMiddleware(ContainerContract $container): void
    {
        $container->setSingleton(
            OutputThrowableCaughtMiddleware::class,
            new OutputThrowableCaughtMiddleware()
        );
    }

    /**
     * Publish the CheckForHelpOptionsMiddleware service.
     */
    public static function publishCheckForHelpOptionsMiddleware(ContainerContract $container): void
    {
        $env = $container->getSingleton(Env::class);

        /** @var non-empty-string $commandName */
        $commandName = $env::CLI_HELP_COMMAND_NAME
            ?? HelpCommand::NAME;
        /** @var non-empty-string $name */
        $name = $env::CLI_HELP_OPTION_NAME
            ?? OptionName::HELP;
        /** @var non-empty-string $shortName */
        $shortName = $env::CLI_HELP_OPTION_SHORT_NAME
            ?? OptionShortName::HELP;

        $container->setSingleton(
            CheckForHelpOptionsMiddleware::class,
            new CheckForHelpOptionsMiddleware(
                commandName: $commandName,
                optionName: $name,
                optionShortName: $shortName
            )
        );
    }

    /**
     * Publish the CheckForVersionOptionsMiddleware service.
     */
    public static function publishCheckForVersionOptionsMiddleware(ContainerContract $container): void
    {
        $env = $container->getSingleton(Env::class);

        /** @var non-empty-string $commandName */
        $commandName = $env::CLI_VERSION_COMMAND_NAME
            ?? VersionCommand::NAME;
        /** @var non-empty-string $name */
        $name = $env::CLI_VERSION_OPTION_NAME
            ?? OptionName::VERSION;
        /** @var non-empty-string $shortName */
        $shortName = $env::CLI_VERSION_OPTION_SHORT_NAME
            ?? OptionShortName::VERSION;

        $container->setSingleton(
            CheckForVersionOptionsMiddleware::class,
            new CheckForVersionOptionsMiddleware(
                commandName: $commandName,
                optionName: $name,
                optionShortName: $shortName
            )
        );
    }

    /**
     * Publish the CheckGlobalInteractionOptionsMiddleware service.
     */
    public static function publishCheckGlobalInteractionOptionsMiddleware(ContainerContract $container): void
    {
        $container->setSingleton(
            CheckGlobalInteractionOptionsMiddleware::class,
            new CheckGlobalInteractionOptionsMiddleware(
                config: $container->getSingleton(Config::class),
                noInteractionOptionName: OptionName::NO_INTERACTION,
                noInteractionOptionShortName: OptionShortName::NO_INTERACTION,
                quietOptionName: OptionName::QUIET,
                quietOptionShortName: OptionShortName::QUIET,
                silentOptionName: OptionName::SILENT,
                silentOptionShortName: OptionShortName::SILENT
            )
        );
    }
}
