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

namespace Valkyrja\Tests\Unit\Application\Entry;

use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use TypeError;
use Valkyrja\Application\Data\Config;
use Valkyrja\Application\Entry\Http;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the Http service.
 */
#[RunTestsInSeparateProcesses]
final class HttpTest extends TestCase
{
    public function testRunThrowsWhenBaseConfigPassed(): void
    {
        $this->expectException(TypeError::class);

        Http::run(config: new Config());
    }
}
