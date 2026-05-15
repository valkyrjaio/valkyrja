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
}
