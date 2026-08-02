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
use Valkyrja\Container\Data\ContainerData;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Contract\ServiceProviderContract;
use Valkyrja\Tests\Fixtures\Application\Data\CliTestContainerDataFixture;

final class CliContainerDataProviderFixture implements ServiceProviderContract
{
    /**
     * Publish the service.
     */
    public static function publishData(ContainerContract $container): void
    {
        $container->setSingleton(ContainerData::class, new CliTestContainerDataFixture());
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function publishers(): array
    {
        return [
            ContainerData::class => [self::class, 'publishData'],
        ];
    }
}
