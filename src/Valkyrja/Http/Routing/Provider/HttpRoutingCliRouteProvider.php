<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Http\Routing\Provider;

use Override;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Interaction\Output\Factory\Contract\OutputFactoryContract;
use Valkyrja\Cli\Routing\Data\Contract\RouteContract;
use Valkyrja\Cli\Routing\Provider\Contract\CliRouteProviderContract;
use Valkyrja\Cli\Server\Command\VersionCommand;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Http\Routing\Cli\Command\ListCommand;
use Valkyrja\Http\Routing\Collection\Contract\RouteCollectionContract;

class HttpRoutingCliRouteProvider implements CliRouteProviderContract
{
    /**
     * The list command handler.
     */
    public static function listHandler(ContainerContract $container, RouteContract $route): OutputContract
    {
        return $container->getSingleton(ListCommand::class)->run(
            $container->getSingleton(VersionCommand::class),
            $container->getSingleton(RouteCollectionContract::class),
            $container->getSingleton(OutputFactoryContract::class),
        );
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getControllerClasses(): array
    {
        return [
            ListCommand::class,
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
}
