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
use Valkyrja\Application\Env\Env;
use Valkyrja\Cli\Interaction\Data\Config;
use Valkyrja\Cli\Interaction\Factory\Contract\OutputFactoryContract;
use Valkyrja\Cli\Interaction\Factory\OutputFactory;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Provider;

final class ServiceProvider extends Provider
{
    /**
     * @inheritDoc
     */
    #[Override]
    public static function publishers(): array
    {
        return [
            Config::class                => [self::class, 'publishConfig'],
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
            Config::class,
            OutputFactoryContract::class,
        ];
    }

    /**
     * Publish the output factory.
     */
    public static function publishConfig(ContainerContract $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var bool $isQuiet */
        $isQuiet = $env::CLI_INTERACTION_IS_QUIET
            ?? false;
        /** @var bool $isInteractive */
        $isInteractive = $env::CLI_INTERACTION_IS_INTERACTIVE
            ?? true;
        /** @var bool $isSilent */
        $isSilent = $env::CLI_INTERACTION_IS_SILENT
            ?? false;

        $container->setSingleton(
            Config::class,
            new Config(
                isQuiet: $isQuiet,
                isInteractive: $isInteractive,
                isSilent: $isSilent,
            )
        );
    }

    /**
     * Publish the output factory.
     */
    public static function publishOutputFactory(ContainerContract $container): void
    {
        $config = $container->getSingleton(Config::class);

        $container->setSingleton(
            OutputFactoryContract::class,
            new OutputFactory(
                config: $config
            )
        );
    }
}
