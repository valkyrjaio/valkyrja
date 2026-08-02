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
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Contract\ServiceProviderContract;
use Valkyrja\Http\Routing\Cli\Command\ListCommand;

class HttpRoutingCliServiceProvider implements ServiceProviderContract
{
    /**
     * Publish the list command service.
     */
    public static function publishListCommand(ContainerContract $container): void
    {
        $container->setSingleton(
            ListCommand::class,
            new ListCommand()
        );
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function publishers(): array
    {
        return [
            ListCommand::class => [self::class, 'publishListCommand'],
        ];
    }
}
