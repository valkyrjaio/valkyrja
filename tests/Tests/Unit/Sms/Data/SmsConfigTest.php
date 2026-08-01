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

use Valkyrja\Sms\Data\Contract\SmsConfigContract;
use Valkyrja\Sms\Data\SmsConfig;
use Valkyrja\Sms\Data\SmsVonageConfig;
use Valkyrja\Sms\Messenger\NullMessenger;
use Valkyrja\Sms\Messenger\VonageMessenger;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class SmsConfigTest extends TestCase
{
    public function testImplementsContract(): void
    {
        self::assertInstanceOf(SmsConfigContract::class, new SmsConfig());
    }

    public function testDefaults(): void
    {
        $config = new SmsConfig();

        self::assertSame(VonageMessenger::class, $config->defaultMessenger);
        self::assertSame('vonage-key', $config->vonage->key);
    }

    public function testCustomValuesAreStored(): void
    {
        $vonage = new SmsVonageConfig(key: 'test-key');

        $config = new SmsConfig(
            defaultMessenger: NullMessenger::class,
            vonage: $vonage,
        );

        self::assertSame(NullMessenger::class, $config->defaultMessenger);
        self::assertSame($vonage, $config->vonage);
    }
}
