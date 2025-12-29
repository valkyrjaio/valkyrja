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

namespace Valkyrja\Attribute\Provider;

use Override;
use Valkyrja\Attribute\Collector\Contract\Collector;
use Valkyrja\Container\Manager\Contract\Container;
use Valkyrja\Container\Provider\Provider;
use Valkyrja\Reflection\Reflector\Contract\Reflector;

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
            Collector::class => [self::class, 'publishAttributes'],
        ];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function provides(): array
    {
        return [
            Collector::class,
        ];
    }

    /**
     * Publish the attributes service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishAttributes(Container $container): void
    {
        $container->setSingleton(
            Collector::class,
            new \Valkyrja\Attribute\Collector\Collector(
                $container->getSingleton(Reflector::class),
            )
        );
    }
}
