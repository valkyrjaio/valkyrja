<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Cli\Interaction\Input;

use Override;
use Valkyrja\Cli\Interaction\Input\Input;
use Valkyrja\Tests\Fixtures\Throwable\Exception\ValkyrjaRuntimeExceptionFixture;

/**
 * Testable Input class whose command name raises, so a report of a throwable raises with it.
 */
final class InputRaisingCommandNameFixture extends Input
{
    #[Override]
    public function getCommandName(): string
    {
        throw new ValkyrjaRuntimeExceptionFixture('The input failed.');
    }
}
