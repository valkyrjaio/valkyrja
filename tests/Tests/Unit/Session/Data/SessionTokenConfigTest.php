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

use Valkyrja\Session\Data\Contract\SessionTokenConfigContract;
use Valkyrja\Session\Data\SessionTokenConfig;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class SessionTokenConfigTest extends TestCase
{
    public function testImplementsContract(): void
    {
        self::assertInstanceOf(SessionTokenConfigContract::class, new SessionTokenConfig());
    }

    public function testDefaults(): void
    {
        $config = new SessionTokenConfig();

        self::assertNull($config->tokenOptionName);
        self::assertNull($config->tokenHeaderName);
    }

    public function testCustomValuesAreStored(): void
    {
        $config = new SessionTokenConfig(tokenOptionName: 'test-option', tokenHeaderName: 'test-header');

        self::assertSame('test-option', $config->tokenOptionName);
        self::assertSame('test-header', $config->tokenHeaderName);
    }
}
