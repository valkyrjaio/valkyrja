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

namespace Valkyrja\Asset\Provider;

use Valkyrja\Asset\Adapter\DefaultAdapter;
use Valkyrja\Asset\Contract\Asset;
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
            DefaultAdapter::class => [self::class, 'publishDefaultAdapter'],
            Asset::class          => [self::class, 'publishAsset'],
        ];
    }

    /**
     * @inheritDoc
     */
    public static function provides(): array
    {
        return [
            DefaultAdapter::class,
            Asset::class,
        ];
    }

    /**
     * Provider the default adapter service.
     */
    public static function publishDefaultAdapter(Container $container): void
    {
    }

    /**
     * Provider the Asset service.
     */
    public static function publishAsset(Container $container): void
    {
    }
}
