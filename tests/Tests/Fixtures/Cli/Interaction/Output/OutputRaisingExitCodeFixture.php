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
use Valkyrja\Cli\Interaction\Enum\ExitCode;
use Valkyrja\Cli\Interaction\Output\Output;
use Valkyrja\Tests\Fixtures\Throwable\Exception\ValkyrjaRuntimeExceptionFixture;

/**
 * Testable Output whose exit code raises, so a read of it raises with it.
 */
final class OutputRaisingExitCodeFixture extends Output
{
    #[Override]
    public function getExitCode(): ExitCode|int
    {
        throw new ValkyrjaRuntimeExceptionFixture('The exit code failed.');
    }
}
