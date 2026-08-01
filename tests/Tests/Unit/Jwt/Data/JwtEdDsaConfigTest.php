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

use Valkyrja\Jwt\Data\Contract\JwtEdDsaConfigContract;
use Valkyrja\Jwt\Data\JwtEdDsaConfig;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class JwtEdDsaConfigTest extends TestCase
{
    public function testImplementsContract(): void
    {
        self::assertInstanceOf(JwtEdDsaConfigContract::class, new JwtEdDsaConfig());
    }

    public function testDefaults(): void
    {
        $config = new JwtEdDsaConfig();

        self::assertSame('private-key', $config->edDsaPrivateKey);
        self::assertSame('public-key', $config->edDsaPublicKey);
    }

    public function testCustomValuesAreStored(): void
    {
        $config = new JwtEdDsaConfig(edDsaPrivateKey: 'test-private', edDsaPublicKey: 'test-public');

        self::assertSame('test-private', $config->edDsaPrivateKey);
        self::assertSame('test-public', $config->edDsaPublicKey);
    }
}
