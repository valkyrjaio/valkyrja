<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Cli\Interaction\Provider;

use Override;
use Valkyrja\Application\Data\Contract\ConfigContract;
use Valkyrja\Cli\Interaction\Data\CliInteractionConfig;
use Valkyrja\Cli\Interaction\Data\Contract\CliInteractionConfigContract;
use Valkyrja\Cli\Interaction\Output\Factory\Contract\OutputFactoryContract;
use Valkyrja\Cli\Interaction\Output\Factory\OutputFactory;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Contract\ServiceProviderContract;

class CliInteractionServiceProvider implements ServiceProviderContract
{
    /**
     * Publish the output factory.
     */
    public static function publishConfig(ContainerContract $container): void
    {
        $config = $container->getSingleton(ConfigContract::class);

        if ($config instanceof CliInteractionConfigContract) {
            $container->setSingleton(CliInteractionConfigContract::class, $config);

            return;
        }

        $container->setSingleton(
            CliInteractionConfigContract::class,
            new CliInteractionConfig()
        );
    }

    /**
     * Publish the output factory.
     */
    public static function publishOutputFactory(ContainerContract $container): void
    {
        $config = $container->getSingleton(CliInteractionConfigContract::class);

        $container->setSingleton(
            OutputFactoryContract::class,
            new OutputFactory(
                config: $config
            )
        );
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function publishers(): array
    {
        return [
            CliInteractionConfigContract::class => [self::class, 'publishConfig'],
            OutputFactoryContract::class        => [self::class, 'publishOutputFactory'],
        ];
    }
}
