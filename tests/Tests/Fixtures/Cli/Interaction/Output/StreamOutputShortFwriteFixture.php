<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Cli\Interaction\Output;

use Override;
use Valkyrja\Cli\Interaction\Output\StreamOutput;

/**
 * Testable StreamOutput class whose stream stops taking data after one byte.
 */
final class StreamOutputShortFwriteFixture extends StreamOutput
{
    protected int $calls = 0;

    #[Override]
    protected function fwrite($stream, string $data): int|false
    {
        $this->calls++;

        return $this->calls === 1 ? 1 : 0;
    }
}
