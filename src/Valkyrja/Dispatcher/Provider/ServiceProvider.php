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

use Override;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Dispatcher\Contract\Dispatcher;

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
            Dispatcher::class => [self::class, 'publishDispatcher'],
        ];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function provides(): array
    {
        return [
            Dispatcher::class,
        ];
    }

    /**
     * Publish the dispatcher service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishDispatcher(Container $container): void
    {
        $container->setSingleton(
            Dispatcher::class,
            new \Valkyrja\Dispatcher\Dispatcher(
                container: $container
            )
        );
    }
}
