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

use Valkyrja\Application\Config\Valkyrja;
use Valkyrja\Cli\Interaction\Factory\Contract\OutputFactory;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Container\Support\Provider;

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
    public static function publishers(): array
    {
        return [
            OutputFactory::class => [self::class, 'publishOutputFactory'],
        ];
    }

    /**
     * @inheritDoc
     */
    public static function provides(): array
    {
        return [
            OutputFactory::class,
        ];
    }

    /**
     * Publish the output factory.
     */
    public static function publishOutputFactory(Container $container): void
    {
        $config = $container->getSingleton(Valkyrja::class);

        $container->setSingleton(
            OutputFactory::class,
            new \Valkyrja\Cli\Interaction\Factory\OutputFactory(
                config: $config->cli->interaction
            )
        );
    }
}
