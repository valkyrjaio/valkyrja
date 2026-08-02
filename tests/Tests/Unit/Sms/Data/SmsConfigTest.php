<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Sms\Data;

use Valkyrja\Sms\Data\Contract\SmsConfigContract;
use Valkyrja\Sms\Data\SmsConfig;
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
        self::assertSame(VonageMessenger::class, new SmsConfig()->defaultMessenger);
    }

    public function testCustomValuesAreStored(): void
    {
        self::assertSame(
            NullMessenger::class,
            new SmsConfig(defaultMessenger: NullMessenger::class)->defaultMessenger
        );
    }
}
