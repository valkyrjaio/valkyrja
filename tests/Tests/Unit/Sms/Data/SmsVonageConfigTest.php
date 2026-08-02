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
