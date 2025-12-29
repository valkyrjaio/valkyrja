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
use Valkyrja\Application\Env;
use Valkyrja\Cli\Interaction\Data\Config;
use Valkyrja\Cli\Interaction\Factory\Contract\OutputFactory;
use Valkyrja\Cli\Interaction\Factory\OutputFactory as DefaultOutputFactory;
use Valkyrja\Container\Manager\Contract\Container;
use Valkyrja\Container\Provider\Provider;

/**
 * Class ServiceProvider.
 *
 * @author Melech Mizrachi
 */
final class ServiceProvider extends Provider
{
    /**
     * @inheritDoc
     */
    #[Override]
    public static function publishers(): array
    {
        return [
            Config::class        => [self::class, 'publishConfig'],
            OutputFactory::class => [self::class, 'publishOutputFactory'],
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
            OutputFactory::class,
        ];
    }

    /**
     * Publish the output factory.
     */
    public static function publishConfig(Container $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var bool $isQuiet */
        $isQuiet = $env::CLI_INTERACTION_IS_QUIET;
        /** @var bool $isInteractive */
        $isInteractive = $env::CLI_INTERACTION_IS_INTERACTIVE;
        /** @var bool $isSilent */
        $isSilent = $env::CLI_INTERACTION_IS_SILENT;

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
    public static function publishOutputFactory(Container $container): void
    {
        $config = $container->getSingleton(Config::class);

        $container->setSingleton(
            OutputFactory::class,
            new DefaultOutputFactory(
                config: $config
            )
        );
    }
}
