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

namespace Valkyrja\Tests\Unit\Http\Routing\Support;

use Valkyrja\Http\Routing\Support\Helpers;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Class DateFactoryTest.
 */
class HelpersTest extends TestCase
{
    public function testTrimPath(): void
    {
        self::assertSame('/test', Helpers::trimPath('test'));
        self::assertSame('/test', Helpers::trimPath('/test'));
    }
}
