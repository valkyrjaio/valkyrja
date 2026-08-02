<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Application\Provider;

use Override;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Contract\ServiceProviderContract;
use Valkyrja\Http\Routing\Data\HttpRoutingData;
use Valkyrja\Tests\Fixtures\Application\Data\HttpTestHttpRoutingDataFixture;

final class HttpRoutingDataProviderFixture implements ServiceProviderContract
{
    public static bool $published = false;

    /**
     * Publish the service.
     */
    public static function publishData(ContainerContract $container): void
    {
        self::$published = true;

        $container->setSingleton(HttpRoutingData::class, new HttpTestHttpRoutingDataFixture());
    }

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
}
