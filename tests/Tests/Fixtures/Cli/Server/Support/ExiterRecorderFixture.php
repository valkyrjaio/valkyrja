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

namespace Valkyrja\Tests\Fixtures\Cli\Server\Support;

use Override;
use Valkyrja\Cli\Server\Support\Exiter;

/**
 * Records the exit seam call made by Exiter when it is not frozen, so tests can
 * exercise the unfrozen arm without terminating the test process.
 */
final class ExiterRecorderFixture extends Exiter
{
    /** The code the exit seam was called with, or null if it was not called. */
    public static int|null $exitedWithCode = null;

    /**
     * Forget any recorded exit seam call.
     */
    public static function reset(): void
    {
        self::$exitedWithCode = null;
    }

    #[Override]
    protected static function exitCallback(int $code = 0): void
    {
        self::$exitedWithCode = $code;
    }
}
