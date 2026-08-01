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

use Valkyrja\Mail\Data\Contract\MailPhpMailerConfigContract;
use Valkyrja\Mail\Data\MailPhpMailerConfig;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class MailPhpMailerConfigTest extends TestCase
{
    public function testImplementsContract(): void
    {
        self::assertInstanceOf(MailPhpMailerConfigContract::class, new MailPhpMailerConfig());
    }

    public function testDefaults(): void
    {
        $config = new MailPhpMailerConfig();

        self::assertSame('host', $config->phpMailerHost);
        self::assertSame(25, $config->phpMailerPort);
        self::assertSame('username', $config->phpMailerUsername);
        self::assertSame('password', $config->phpMailerPassword);
        self::assertSame('ssl', $config->phpMailerEncryption);
    }

    public function testCustomValuesAreStored(): void
    {
        $config = new MailPhpMailerConfig(
            phpMailerHost: 'test-host',
            phpMailerPort: 587,
            phpMailerUsername: 'test-username',
            phpMailerPassword: 'test-password',
            phpMailerEncryption: 'tls',
        );

        self::assertSame('test-host', $config->phpMailerHost);
        self::assertSame(587, $config->phpMailerPort);
        self::assertSame('test-username', $config->phpMailerUsername);
        self::assertSame('test-password', $config->phpMailerPassword);
        self::assertSame('tls', $config->phpMailerEncryption);
    }
}
