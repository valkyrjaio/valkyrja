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

namespace Valkyrja\Tests\Unit\Application\Entry\RoadRunner\Constant;

use Valkyrja\Application\Entry\RoadRunner\Constant\RoadRunnerInfo;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the RoadRunnerInfo constant.
 */
final class RoadRunnerInfoTest extends TestCase
{
    public function testVersion(): void
    {
        self::assertSame('26.0.2', RoadRunnerInfo::VERSION);
    }

    public function testVersionBuildDateTime(): void
    {
        self::assertSame('April 17 2026 00:00:00 MST', RoadRunnerInfo::VERSION_BUILD_DATE_TIME);
    }
}
