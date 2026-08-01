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
