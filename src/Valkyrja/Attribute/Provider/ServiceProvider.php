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
use Valkyrja\Attribute\Contract\Attributes;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Reflection\Contract\Reflection;

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
            Attributes::class => [self::class, 'publishAttributes'],
        ];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function provides(): array
    {
        return [
            Attributes::class,
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
            Attributes::class,
            new \Valkyrja\Attribute\Attributes(
                $container->getSingleton(Reflection::class),
            )
        );
    }
}
