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
use Valkyrja\Application\Data\CliConfig;
use Valkyrja\Application\Data\Contract\ConfigContract;
use Valkyrja\Application\Env\Env;
use Valkyrja\Cli\Interaction\Data\Contract\CliInteractionConfigContract;
use Valkyrja\Cli\Interaction\Output\Factory\Contract\OutputFactoryContract;
use Valkyrja\Cli\Middleware\Handler\Contract\ExitedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\InputReceivedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\ThrowableCaughtHandlerContract;
use Valkyrja\Cli\Routing\Collection\Contract\RouteCollectionContract;
use Valkyrja\Cli\Routing\Constant\OptionName;
use Valkyrja\Cli\Routing\Constant\OptionShortName;
use Valkyrja\Cli\Routing\Data\Contract\RouteContract;
use Valkyrja\Cli\Routing\Dispatcher\Contract\RouterContract;
use Valkyrja\Cli\Server\Command\GenerateDataCommand;
use Valkyrja\Cli\Server\Command\HelpCommand;
use Valkyrja\Cli\Server\Command\ListBashCommand;
use Valkyrja\Cli\Server\Command\ListCommand;
use Valkyrja\Cli\Server\Command\VersionCommand;
use Valkyrja\Cli\Server\Constant\CommandName;
use Valkyrja\Cli\Server\Data\Contract\CliHelpCommandConfigContract;
use Valkyrja\Cli\Server\Data\Contract\CliNoInteractionConfigContract;
use Valkyrja\Cli\Server\Data\Contract\CliQuietInteractionConfigContract;
use Valkyrja\Cli\Server\Data\Contract\CliSilentInteractionConfigContract;
use Valkyrja\Cli\Server\Data\Contract\CliVersionCommandConfigContract;
use Valkyrja\Cli\Server\Handler\Contract\InputHandlerContract;
use Valkyrja\Cli\Server\Handler\InputHandler;
use Valkyrja\Cli\Server\Middleware\InputReceived\CheckForHelpOptionsMiddleware;
use Valkyrja\Cli\Server\Middleware\InputReceived\CheckForVersionOptionsMiddleware;
use Valkyrja\Cli\Server\Middleware\InputReceived\CheckGlobalInteractionOptionsMiddleware;
use Valkyrja\Cli\Server\Middleware\RouteNotMatched\CheckCommandForTypoMiddleware;
use Valkyrja\Cli\Server\Middleware\ThrowableCaught\LogThrowableCaughtMiddleware;
use Valkyrja\Cli\Server\Middleware\ThrowableCaught\OutputThrowableCaughtMiddleware;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Contract\ServiceProviderContract;
use Valkyrja\Log\Logger\Contract\LoggerContract;

class CliServerServiceProvider implements ServiceProviderContract
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
            GenerateDataCommand::class                     => [self::class, 'publishGenerateDataCommand'],
            LogThrowableCaughtMiddleware::class            => [self::class, 'publishLogThrowableCaughtMiddleware'],
            OutputThrowableCaughtMiddleware::class         => [self::class, 'publishOutputThrowableCaughtMiddleware'],
            CheckForHelpOptionsMiddleware::class           => [self::class, 'publishCheckForHelpOptionsMiddleware'],
            CheckForVersionOptionsMiddleware::class        => [self::class, 'publishCheckForVersionOptionsMiddleware'],
            CheckGlobalInteractionOptionsMiddleware::class => [self::class, 'publishCheckGlobalInteractionOptionsMiddleware'],
            CheckCommandForTypoMiddleware::class           => [self::class, 'publishCheckCommandForTypoMiddleware'],
        ];
    }

    /**
     * Publish the input handler service.
     */
    public static function publishInputHandler(ContainerContract $container): void
    {
        $config = $container->getSingleton(CliInteractionConfigContract::class);

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
                collection: $container->getSingleton(RouteCollectionContract::class),
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
                collection: $container->getSingleton(RouteCollectionContract::class),
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
                collection: $container->getSingleton(RouteCollectionContract::class),
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
     * Publish the GenerateDataCommand service.
     */
    public static function publishGenerateDataCommand(ContainerContract $container): void
    {
        $container->setSingleton(
            GenerateDataCommand::class,
            new GenerateDataCommand(
                env: $container->getSingleton(Env::class),
                config: $container->getSingleton(CliConfig::class),
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
        $config = $container->getSingleton(ConfigContract::class);

        $commandName = CommandName::HELP;
        $name        = OptionName::HELP;
        $shortName   = OptionShortName::HELP;

        if ($config instanceof CliHelpCommandConfigContract) {
            $commandName = $config->helpCommandName;
            $name        = $config->helpOptionName;
            $shortName   = $config->helpOptionShortName;
        }

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
        $config = $container->getSingleton(ConfigContract::class);

        $commandName = CommandName::VERSION;
        $name        = OptionName::VERSION;
        $shortName   = OptionShortName::VERSION;

        if ($config instanceof CliVersionCommandConfigContract) {
            $commandName = $config->versionCommandName;
            $name        = $config->versionOptionName;
            $shortName   = $config->versionOptionShortName;
        }

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
        $config = $container->getSingleton(ConfigContract::class);

        $noInteractionOptionName      = OptionName::NO_INTERACTION;
        $noInteractionOptionShortName = OptionShortName::NO_INTERACTION;

        $isQuietOptionName      = OptionName::QUIET;
        $isQuietOptionShortName = OptionShortName::QUIET;

        $isSilentOptionName      = OptionName::SILENT;
        $isSilentOptionShortName = OptionShortName::SILENT;

        if ($config instanceof CliNoInteractionConfigContract) {
            $noInteractionOptionName      = $config->noInteractionOptionName;
            $noInteractionOptionShortName = $config->noInteractionOptionShortName;
        }

        if ($config instanceof CliQuietInteractionConfigContract) {
            $isQuietOptionName      = $config->quietOptionName;
            $isQuietOptionShortName = $config->quietOptionShortName;
        }

        if ($config instanceof CliSilentInteractionConfigContract) {
            $isSilentOptionName      = $config->silentOptionName;
            $isSilentOptionShortName = $config->silentOptionShortName;
        }

        $container->setSingleton(
            CheckGlobalInteractionOptionsMiddleware::class,
            new CheckGlobalInteractionOptionsMiddleware(
                config: $container->getSingleton(CliInteractionConfigContract::class),
                noInteractionOptionName: $noInteractionOptionName,
                noInteractionOptionShortName: $noInteractionOptionShortName,
                quietOptionName: $isQuietOptionName,
                quietOptionShortName: $isQuietOptionShortName,
                silentOptionName: $isSilentOptionName,
                silentOptionShortName: $isSilentOptionShortName
            )
        );
    }

    /**
     * Publish the check command for typo middleware service.
     */
    public static function publishCheckCommandForTypoMiddleware(ContainerContract $container): void
    {
        $container->setSingleton(
            CheckCommandForTypoMiddleware::class,
            new CheckCommandForTypoMiddleware(
                router: $container->getSingleton(RouterContract::class),
                collection: $container->getSingleton(RouteCollectionContract::class),
            )
        );
    }
}
