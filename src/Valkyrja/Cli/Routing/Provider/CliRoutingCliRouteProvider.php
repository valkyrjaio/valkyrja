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

namespace Valkyrja\Cli\Routing\Provider;

use Override;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Routing\Data\Contract\RouteContract;
use Valkyrja\Cli\Routing\Provider\Contract\CliRouteProviderContract;
use Valkyrja\Cli\Server\Command\HelpCommand;
use Valkyrja\Cli\Server\Command\ListBashCommand;
use Valkyrja\Cli\Server\Command\ListCommand;
use Valkyrja\Cli\Server\Command\VersionCommand;
use Valkyrja\Container\Manager\Contract\ContainerContract;

class CliRoutingCliRouteProvider implements CliRouteProviderContract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function getControllerClasses(): array
    {
        return [
            HelpCommand::class,
            ListBashCommand::class,
            ListCommand::class,
            VersionCommand::class,
        ];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getRoutes(): array
    {
        return [];
    }

    /**
     * The list command handler.
     */
    public static function listHandler(ContainerContract $container, RouteContract $route): OutputContract
    {
        return $container->getSingleton(ListCommand::class)->run();
    }

    /**
     * The list bash command handler.
     */
    public static function listBashHandler(ContainerContract $container, RouteContract $route): OutputContract
    {
        return $container->getSingleton(ListBashCommand::class)->run();
    }

    /**
     * The help command handler.
     */
    public static function helpHandler(ContainerContract $container, RouteContract $route): OutputContract
    {
        return $container->getSingleton(HelpCommand::class)->run();
    }

    /**
     * The version command handler.
     */
    public static function versionHandler(ContainerContract $container, RouteContract $route): OutputContract
    {
        return $container->getSingleton(VersionCommand::class)->run();
    }
}
