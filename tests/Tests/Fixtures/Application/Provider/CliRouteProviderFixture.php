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

namespace Valkyrja\Tests\Fixtures\Application\Provider;

use Override;
use Valkyrja\Cli\Routing\Provider\Contract\CliRouteProviderContract;
use Valkyrja\Tests\Functional\Application\Entry\CliTest;

final class CliRouteProviderFixture implements CliRouteProviderContract
{
    public static bool $called = false;

    /**
     * @inheritDoc
     */
    #[Override]
    public function getControllerClasses(): array
    {
        self::$called = true;

        return [
            CliTest::class,
        ];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getRoutes(): array
    {
        return [];
    }
}
