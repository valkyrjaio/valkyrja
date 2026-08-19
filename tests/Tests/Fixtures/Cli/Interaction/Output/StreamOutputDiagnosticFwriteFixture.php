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

use function error_clear_last;
use function trigger_error;

use const E_USER_WARNING;

/**
 * Testable StreamOutput class whose stream raises a diagnostic and takes nothing.
 */
final class StreamOutputDiagnosticFwriteFixture extends StreamOutput
{
    #[Override]
    protected function fwrite($stream, string $data): int|false
    {
        error_clear_last();

        @trigger_error('Write of 4 bytes failed with errno=32 Broken pipe', E_USER_WARNING);

        return false;
    }
}
