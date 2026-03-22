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

namespace Valkyrja\Cli\Interaction\Provider;

use Override;
use Valkyrja\Application\Data\Config as ApplicationConfig;
use Valkyrja\Cli\Interaction\Data\Config;
use Valkyrja\Cli\Interaction\Data\Contract\ConfigContract;
use Valkyrja\Cli\Interaction\Output\Factory\Contract\OutputFactoryContract;
use Valkyrja\Cli\Interaction\Output\Factory\OutputFactory;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Abstract\Provider;

final class ServiceProvider extends Provider
{
    /**
     * @inheritDoc
     */
    #[Override]
    public static function publishers(): array
    {
        return [
            ConfigContract::class        => [self::class, 'publishConfig'],
            OutputFactoryContract::class => [self::class, 'publishOutputFactory'],
        ];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function provides(): array
    {
        return [
            ConfigContract::class,
            OutputFactoryContract::class,
        ];
    }

    /**
     * Publish the output factory.
     */
    public static function publishConfig(ContainerContract $container): void
    {
        $config = $container->getSingleton(ApplicationConfig::class);

        if ($config instanceof ConfigContract) {
            $container->setSingleton(ConfigContract::class, $config);

            return;
        }

        $container->setSingleton(
            ConfigContract::class,
            new Config()
        );
    }

    /**
     * Publish the output factory.
     */
    public static function publishOutputFactory(ContainerContract $container): void
    {
        $config = $container->getSingleton(ConfigContract::class);

        $container->setSingleton(
            OutputFactoryContract::class,
            new OutputFactory(
                config: $config
            )
        );
    }
}
