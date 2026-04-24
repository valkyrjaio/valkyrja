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
use Valkyrja\Cli\Routing\Data\CliRoutingData;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Contract\ServiceProviderContract;
use Valkyrja\Tests\Classes\Application\Data\CliTestCliRoutingData;

final class CliRoutingDataProviderClass implements ServiceProviderContract
{
    public static bool $published = false;

    /**
     * @inheritDoc
     */
    #[Override]
    public static function publishers(): array
    {
        return [
            CliRoutingData::class => [self::class, 'publishData'],
        ];
    }

    /**
     * Publish the service.
     */
    public static function publishData(ContainerContract $container): void
    {
        self::$published = true;

        $container->setSingleton(CliRoutingData::class, new CliTestCliRoutingData());
    }
}
