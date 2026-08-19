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
 * Testable StreamOutput class that writes part of the data.
 */
final class StreamOutputShortFwriteFixture extends StreamOutput
{
    #[Override]
    protected function fwrite($stream, string $data): int|false
    {
        return 1;
    }
}
