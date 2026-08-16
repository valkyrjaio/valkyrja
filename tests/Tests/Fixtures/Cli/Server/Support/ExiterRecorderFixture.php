<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Cli\Server\Support;

use Override;
use Valkyrja\Cli\Server\Support\Exiter;

/**
 * Records Exiter's unfrozen exit seam call without terminating the test process.
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
