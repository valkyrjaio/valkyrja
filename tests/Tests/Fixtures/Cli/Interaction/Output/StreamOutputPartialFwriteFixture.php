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

use function substr;

/**
 * Testable StreamOutput class whose stream takes one byte per call.
 */
final class StreamOutputPartialFwriteFixture extends StreamOutput
{
    #[Override]
    protected function fwrite($stream, string $data): int|false
    {
        return parent::fwrite($stream, substr($data, 0, 1));
    }
}
