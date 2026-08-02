<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Cli\Server\Data;

use Valkyrja\Cli\Server\Data\Contract\CliHelpCommandConfigContract;
use Valkyrja\Cli\Server\Data\Contract\CliNoInteractionConfigContract;
use Valkyrja\Cli\Server\Data\Contract\CliQuietInteractionConfigContract;
use Valkyrja\Cli\Server\Data\Contract\CliSilentInteractionConfigContract;
use Valkyrja\Cli\Server\Data\Contract\CliVersionCommandConfigContract;

final class CliCommandCommandConfigFixture implements CliHelpCommandConfigContract, CliVersionCommandConfigContract, CliNoInteractionConfigContract, CliQuietInteractionConfigContract, CliSilentInteractionConfigContract
{
    public function __construct(
        public string $helpCommandName = '',
        public string $helpOptionName = '',
        public string $helpOptionShortName = '',
        public string $versionCommandName = '',
        public string $versionOptionName = '',
        public string $versionOptionShortName = '',
        public string $noInteractionOptionName = '',
        public string $noInteractionOptionShortName = '',
        public string $quietOptionName = '',
        public string $quietOptionShortName = '',
        public string $silentOptionName = '',
        public string $silentOptionShortName = '',
    ) {
    }
}
