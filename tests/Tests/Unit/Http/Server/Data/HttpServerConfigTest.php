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

namespace Valkyrja\Tests\Unit\Http\Server\Data;

use Valkyrja\Http\Server\Data\Contract\HttpServerConfigContract;
use Valkyrja\Http\Server\Data\HttpServerConfig;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class HttpServerConfigTest extends TestCase
{
    public function testImplementsContract(): void
    {
        self::assertInstanceOf(HttpServerConfigContract::class, new HttpServerConfig());
    }

    public function testDefaults(): void
    {
        self::assertNull(new HttpServerConfig()->responseCacheFilePath);
    }

    public function testCustomValuesAreStored(): void
    {
        $config = new HttpServerConfig(responseCacheFilePath: '/tmp/response-cache');

        self::assertSame('/tmp/response-cache', $config->responseCacheFilePath);
    }
}
