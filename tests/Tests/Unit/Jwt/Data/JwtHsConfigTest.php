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

use Valkyrja\Jwt\Data\Contract\JwtHsConfigContract;
use Valkyrja\Jwt\Data\JwtHsConfig;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class JwtHsConfigTest extends TestCase
{
    public function testImplementsContract(): void
    {
        self::assertInstanceOf(JwtHsConfigContract::class, new JwtHsConfig());
    }

    public function testDefaults(): void
    {
        self::assertSame('key', new JwtHsConfig()->hsKey);
    }

    public function testCustomValuesAreStored(): void
    {
        self::assertSame('test-key', new JwtHsConfig(hsKey: 'test-key')->hsKey);
    }
}
