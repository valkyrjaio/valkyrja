<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Mail\Data;

use Valkyrja\Mail\Data\Contract\MailMailgunConfigContract;
use Valkyrja\Mail\Data\MailMailgunConfig;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class MailMailgunConfigTest extends TestCase
{
    public function testImplementsContract(): void
    {
        self::assertInstanceOf(MailMailgunConfigContract::class, new MailMailgunConfig());
    }

    public function testDefaults(): void
    {
        $config = new MailMailgunConfig();

        self::assertSame('domain', $config->mailgunDomain);
        self::assertSame('api-key', $config->mailgunApiKey);
    }

    public function testCustomValuesAreStored(): void
    {
        $config = new MailMailgunConfig(mailgunDomain: 'test-domain', mailgunApiKey: 'test-api-key');

        self::assertSame('test-domain', $config->mailgunDomain);
        self::assertSame('test-api-key', $config->mailgunApiKey);
    }
}
