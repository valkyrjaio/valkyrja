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

namespace Valkyrja\Tests\Unit\Sms\Data;

use Valkyrja\Sms\Data\Contract\SmsVonageConfigContract;
use Valkyrja\Sms\Data\SmsVonageConfig;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class SmsVonageConfigTest extends TestCase
{
    public function testImplementsContract(): void
    {
        self::assertInstanceOf(SmsVonageConfigContract::class, new SmsVonageConfig());
    }

    public function testDefaults(): void
    {
        $config = new SmsVonageConfig();

        self::assertSame('vonage-key', $config->vonageKey);
        self::assertSame('vonage-secret', $config->vonageSecret);
    }

    public function testCustomValuesAreStored(): void
    {
        $config = new SmsVonageConfig(vonageKey: 'test-key', vonageSecret: 'test-secret');

        self::assertSame('test-key', $config->vonageKey);
        self::assertSame('test-secret', $config->vonageSecret);
    }
}
