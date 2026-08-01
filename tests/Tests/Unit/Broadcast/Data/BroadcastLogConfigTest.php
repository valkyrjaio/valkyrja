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

namespace Valkyrja\Tests\Unit\Broadcast\Data;

use Valkyrja\Broadcast\Data\BroadcastLogConfig;
use Valkyrja\Log\Logger\Contract\LoggerContract;
use Valkyrja\Log\Logger\PsrLogger;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class BroadcastLogConfigTest extends TestCase
{
    public function testDefaults(): void
    {
        self::assertSame(LoggerContract::class, new BroadcastLogConfig()->logger);
    }

    public function testCustomValuesAreStored(): void
    {
        self::assertSame(PsrLogger::class, new BroadcastLogConfig(logger: PsrLogger::class)->logger);
    }
}
