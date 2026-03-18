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
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Application\Provider\Contract\PublishableProviderContract;
use Valkyrja\Application\Provider\Provider;

final class HttpComponentProviderClass extends Provider implements PublishableProviderContract
{
    public static bool $publishedContainerData = false;

    /**
     * @inheritDoc
     */
    #[Override]
    public static function getContainerProviders(ApplicationContract $app): array
    {
        return [
            HttpContainerDataProviderClass::class,
            HttpRoutingDataProviderClass::class,
        ];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function getHttpProviders(ApplicationContract $app): array
    {
        return [
            HttpRouteProviderClass::class,
        ];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function publish(ApplicationContract $app): void
    {
        if ($app->getDebugMode()) {
            return;
        }

        self::$publishedContainerData = true;
    }
}
