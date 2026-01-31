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

namespace Valkyrja\Tests\Unit\Http\Message\Request\Factory
{
    use Valkyrja\Http\Message\Request\Factory\ServerFactory;
    use Valkyrja\Tests\Unit\Abstract\TestCase;

    class ServerFactoryTest extends TestCase
    {
        public static bool $defaultAuthorizationTest   = false;
        public static bool $lowercaseAuthorizationTest = false;

        public function testNormalizeServer(): void
        {
            self::$defaultAuthorizationTest = true;

            $defaultServer = ServerFactory::normalizeServer([]);

            self::$defaultAuthorizationTest = false;

            self::assertNull($defaultServer['HTTP_AUTHORIZATION'] ?? null);
        }

        public function testNormalizeServerWithAlreadySet(): void
        {
            $alreadySetServer = ServerFactory::normalizeServer(['HTTP_AUTHORIZATION' => 'already_set']);

            self::assertSame('already_set', $alreadySetServer['HTTP_AUTHORIZATION']);
        }

        public function testNormalizeServerCapitalizedApacheHeaders(): void
        {
            $capitalizedServer = ServerFactory::normalizeServer([]);

            self::assertSame('Authorization', $capitalizedServer['HTTP_AUTHORIZATION']);
        }

        public function testNormalizeServerLowercaseApacheHeaders(): void
        {
            self::$lowercaseAuthorizationTest = true;

            $lowercaseServer = ServerFactory::normalizeServer([]);

            self::$lowercaseAuthorizationTest = false;

            self::assertSame('authorization', $lowercaseServer['HTTP_AUTHORIZATION']);
        }
    }
}

namespace {
    use Valkyrja\Tests\Unit\Http\Message\Request\Factory\ServerFactoryTest;

    function apache_request_headers(): array
    {
        if (ServerFactoryTest::$defaultAuthorizationTest) {
            return [];
        }

        if (ServerFactoryTest::$lowercaseAuthorizationTest) {
            return [
                'authorization' => 'authorization',
            ];
        }

        return [
            'Authorization' => 'Authorization',
        ];
    }
}
