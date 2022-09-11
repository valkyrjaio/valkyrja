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

namespace Valkyrja\Asset\Providers;

use Valkyrja\Asset\Adapters\DefaultAdapter;
use Valkyrja\Asset\Asset;
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
     * @inheritDoc
     */
    public static function publishers(): array
    {
        return [
            DefaultAdapter::class => 'publishDefaultAdapter',
            Asset::class          => 'publishAsset',
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
}
