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
use Valkyrja\Http\Routing\Provider\Provider;
use Valkyrja\Tests\Functional\Application\Entry\HttpTest;

final class HttpRouteProvider extends Provider
{
    public static bool $called = false;

    /**
     * @inheritDoc
     */
    #[Override]
    public static function getControllerClasses(): array
    {
        self::$called = true;

        return [
            HttpTest::class,
        ];
    }
}
