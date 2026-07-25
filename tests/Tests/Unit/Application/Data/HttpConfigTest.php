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

namespace Valkyrja\Tests\Unit\Application\Data;

use Valkyrja\Application\Data\Contract\HttpConfigContract;
use Valkyrja\Application\Data\HttpConfig;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class HttpConfigTest extends TestCase
{
    public function testImplementsContract(): void
    {
        self::assertInstanceOf(HttpConfigContract::class, new HttpConfig());
    }

    public function testDefaults(): void
    {
        $config = new HttpConfig();

        self::assertSame('App', $config->namespace);
        self::assertSame('production', $config->environment);
        self::assertFalse($config->debugMode);
        self::assertSame('UTC', $config->timezone);
        self::assertSame('App/Provider/Data', $config->dataPath);
        self::assertSame('App\\Provider\\Data', $config->dataNamespace);
        self::assertCount(1, $config->providers);
        self::assertCount(1, $config->routeNotMatchedMiddleware);
        self::assertCount(2, $config->throwableCaughtMiddleware);
        self::assertSame([], $config->callbacks);
        self::assertSame([], $config->requestReceivedMiddleware);
        self::assertSame([], $config->routeMatchedMiddleware);
        self::assertSame([], $config->routeDispatchedMiddleware);
        self::assertSame([], $config->sendingResponseMiddleware);
        self::assertSame([], $config->responseSentMiddleware);
    }

    public function testCustomValuesAreStored(): void
    {
        $config = new HttpConfig(
            namespace: 'My',
            debugMode: true,
            environment: 'testing',
        );

        self::assertSame('My', $config->namespace);
        self::assertTrue($config->debugMode);
        self::assertSame('testing', $config->environment);
    }
}
