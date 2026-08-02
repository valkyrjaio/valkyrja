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

use Valkyrja\Mail\Data\Contract\MailConfigContract;
use Valkyrja\Mail\Data\MailConfig;
use Valkyrja\Mail\Mailer\MailgunMailer;
use Valkyrja\Mail\Mailer\NullMailer;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class MailConfigTest extends TestCase
{
    public function testImplementsContract(): void
    {
        self::assertInstanceOf(MailConfigContract::class, new MailConfig());
    }

    public function testDefaults(): void
    {
        self::assertSame(MailgunMailer::class, new MailConfig()->defaultMailer);
    }

    public function testCustomValuesAreStored(): void
    {
        self::assertSame(NullMailer::class, new MailConfig(defaultMailer: NullMailer::class)->defaultMailer);
    }
}
