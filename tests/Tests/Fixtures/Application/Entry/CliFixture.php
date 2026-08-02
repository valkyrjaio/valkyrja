<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Application\Entry;

use Valkyrja\Application\Data\Contract\CliConfigContract;
use Valkyrja\Application\Entry\Cli;
use Valkyrja\Cli\Interaction\Input\Contract\InputContract;

final class CliFixture extends Cli
{
    /**
     * Wrapper to test the getInput method directly.
     */
    public static function getInputExposed(CliConfigContract $config): InputContract
    {
        return self::getInput($config);
    }
}
