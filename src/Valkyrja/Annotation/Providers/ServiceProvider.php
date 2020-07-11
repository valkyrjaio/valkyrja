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

namespace Valkyrja\Annotation\Providers;

use Valkyrja\Container\Container;
use Valkyrja\Container\Support\Provider;

/**
 * Class ServiceProvider.
 *
 * @author Melech Mizrachi
 */
class ServiceProvider extends Provider
{
    /**
     * The items provided by this provider.
     *
     * @return string[]
     */
    public static function publishers(): array
    {
        return [];
    }

    /**
     * The items provided by this provider.
     *
     * @return string[]
     */
    public static function provides(): array
    {
        return [];
    }

    /**
     * Publish the provider.
     *
     * @param Container $container
     *
     * @return void
     */
    public static function publish(Container $container): void
    {
    }
}
