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
use Valkyrja\Cli\Routing\Data\CliRoutingData;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Contract\ServiceProviderContract;
use Valkyrja\Tests\Fixtures\Application\Data\CliTestCliRoutingDataFixture;

final class CliRoutingDataProviderFixture implements ServiceProviderContract
{
    public static bool $published = false;

    /**
     * Publish the service.
     */
    public static function publishData(ContainerContract $container): void
    {
        self::$published = true;

        $container->setSingleton(CliRoutingData::class, new CliTestCliRoutingDataFixture());
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function publishers(): array
    {
        return [
            CliRoutingData::class => [self::class, 'publishData'],
        ];
    }
}
