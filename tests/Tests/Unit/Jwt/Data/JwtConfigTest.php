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

namespace Valkyrja\Tests\Unit\Jwt\Data;

use Valkyrja\Jwt\Data\Contract\JwtConfigContract;
use Valkyrja\Jwt\Data\JwtConfig;
use Valkyrja\Jwt\Enum\Algorithm;
use Valkyrja\Jwt\Manager\FirebaseJwt;
use Valkyrja\Jwt\Manager\NullJwt;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class JwtConfigTest extends TestCase
{
    public function testImplementsContract(): void
    {
        self::assertInstanceOf(JwtConfigContract::class, new JwtConfig());
    }

    public function testDefaults(): void
    {
        $config = new JwtConfig();

        self::assertSame(FirebaseJwt::class, $config->defaultJwt);
        self::assertSame(Algorithm::HS256, $config->algorithm);
    }

    public function testCustomValuesAreStored(): void
    {
        $config = new JwtConfig(
            defaultJwt: NullJwt::class,
            algorithm: Algorithm::RS256,
        );

        self::assertSame(NullJwt::class, $config->defaultJwt);
        self::assertSame(Algorithm::RS256, $config->algorithm);
    }
}
