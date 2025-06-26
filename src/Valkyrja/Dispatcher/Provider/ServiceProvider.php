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

namespace Valkyrja\Dispatcher\Provider;

use Valkyrja\Container\Contract\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Dispatcher\Contract\Dispatcher2;

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
            Dispatcher2::class => [self::class, 'publishDispatcher2'],
        ];
    }

    /**
     * @inheritDoc
     */
    public static function provides(): array
    {
        return [
            Dispatcher2::class,
        ];
    }

    /**
     * Publish the dispatcher service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishDispatcher2(Container $container): void
    {
        $container->setSingleton(
            Dispatcher2::class,
            new \Valkyrja\Dispatcher\Dispatcher2(
                container: $container
            )
        );
    }
}
