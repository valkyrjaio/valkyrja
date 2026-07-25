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

namespace Valkyrja\Tests\Unit\Application\Entry\OpenSwoole\Constant;

use Valkyrja\Application\Entry\OpenSwoole\Constant\OpenSwooleInfo;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the OpenSwooleInfo constant.
 */
final class OpenSwooleInfoTest extends TestCase
{
    public function testVersion(): void
    {
        self::assertSame('26.0.0', OpenSwooleInfo::VERSION);
    }

    public function testVersionBuildDateTime(): void
    {
        self::assertSame('April 17 2026 00:00:00 MST', OpenSwooleInfo::VERSION_BUILD_DATE_TIME);
    }
}
