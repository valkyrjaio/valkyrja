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
        /** @var non-empty-string */
        public string $helpCommandName = 'helpCommandName',
        /** @var non-empty-string */
        public string $helpOptionName = 'helpOptionName',
        /** @var non-empty-string */
        public string $helpOptionShortName = 'helpOptionShortName',
        /** @var non-empty-string */
        public string $versionCommandName = 'versionCommandName',
        /** @var non-empty-string */
        public string $versionOptionName = 'versionOptionName',
        /** @var non-empty-string */
        public string $versionOptionShortName = 'versionOptionShortName',
        /** @var non-empty-string */
        public string $noInteractionOptionName = 'noInteractionOptionName',
        /** @var non-empty-string */
        public string $noInteractionOptionShortName = 'noInteractionOptionShortName',
        /** @var non-empty-string */
        public string $quietOptionName = 'quietOptionName',
        /** @var non-empty-string */
        public string $quietOptionShortName = 'quietOptionShortName',
        /** @var non-empty-string */
        public string $silentOptionName = 'silentOptionName',
        /** @var non-empty-string */
        public string $silentOptionShortName = 'silentOptionShortName',
    ) {
    }
}
