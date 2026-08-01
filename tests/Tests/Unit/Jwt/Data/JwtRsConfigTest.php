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

use Valkyrja\Jwt\Data\Contract\JwtRsConfigContract;
use Valkyrja\Jwt\Data\JwtRsConfig;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class JwtRsConfigTest extends TestCase
{
    public function testImplementsContract(): void
    {
        self::assertInstanceOf(JwtRsConfigContract::class, new JwtRsConfig());
    }

    public function testDefaults(): void
    {
        $config = new JwtRsConfig();

        self::assertSame('private-key', $config->rsPrivateKey);
        self::assertSame('public-key', $config->rsPublicKey);
    }

    public function testCustomValuesAreStored(): void
    {
        $config = new JwtRsConfig(rsPrivateKey: 'test-private', rsPublicKey: 'test-public');

        self::assertSame('test-private', $config->rsPrivateKey);
        self::assertSame('test-public', $config->rsPublicKey);
    }
}
