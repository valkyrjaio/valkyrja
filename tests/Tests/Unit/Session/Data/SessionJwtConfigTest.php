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

namespace Valkyrja\Tests\Unit\Session\Data;

use Valkyrja\Session\Data\Contract\SessionJwtConfigContract;
use Valkyrja\Session\Data\SessionJwtConfig;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class SessionJwtConfigTest extends TestCase
{
    public function testImplementsContract(): void
    {
        self::assertInstanceOf(SessionJwtConfigContract::class, new SessionJwtConfig());
    }

    public function testDefaults(): void
    {
        $config = new SessionJwtConfig();

        self::assertNull($config->jwtOptionName);
        self::assertNull($config->jwtHeaderName);
    }

    public function testCustomValuesAreStored(): void
    {
        $config = new SessionJwtConfig(jwtOptionName: 'test-option', jwtHeaderName: 'test-header');

        self::assertSame('test-option', $config->jwtOptionName);
        self::assertSame('test-header', $config->jwtHeaderName);
    }
}
