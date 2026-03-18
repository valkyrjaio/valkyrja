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

namespace Valkyrja\Tests\Classes\Application\Provider;

use Override;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Abstract\Provider;
use Valkyrja\Http\Routing\Data\Data;

final class HttpRoutingDataProviderClass extends Provider
{
    /**
     * @inheritDoc
     */
    #[Override]
    public static function publishers(): array
    {
        return [
            Data::class => [self::class, 'publishData'],
        ];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function provides(): array
    {
        return [
            Data::class,
        ];
    }

    /**
     * Publish the service.
     */
    public static function publishData(ContainerContract $container): void
    {
        $class = 'App\\Provider\\Data\\HttpTestHttpRoutingData';

        $container->setSingleton(Data::class, new $class());
    }
}
