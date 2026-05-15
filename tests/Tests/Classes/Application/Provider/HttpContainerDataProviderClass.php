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
use Valkyrja\Container\Data\ContainerData;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Contract\ServiceProviderContract;
use Valkyrja\Tests\Classes\Application\Data\HttpTestContainerDataClass;

final class HttpContainerDataProviderClass implements ServiceProviderContract
{
    /**
     * Publish the service.
     */
    public static function publishData(ContainerContract $container): void
    {
        $container->setSingleton(ContainerData::class, new HttpTestContainerDataClass());
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
