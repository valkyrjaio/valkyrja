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
use Valkyrja\Container\Provider\Contract\ServiceProviderContract;
use Valkyrja\Http\Routing\Data\HttpRoutingData;
use Valkyrja\Tests\Classes\Application\Data\HttpTestHttpRoutingDataClass;

final class HttpRoutingDataProviderClass implements ServiceProviderContract
{
    public static bool $published = false;

    /**
     * @inheritDoc
     */
    #[Override]
    public function publishers(): array
    {
        return [
            HttpRoutingData::class => [self::class, 'publishData'],
        ];
    }

    /**
     * Publish the service.
     */
    public static function publishData(ContainerContract $container): void
    {
        self::$published = true;

        $container->setSingleton(HttpRoutingData::class, new HttpTestHttpRoutingDataClass());
    }
}
