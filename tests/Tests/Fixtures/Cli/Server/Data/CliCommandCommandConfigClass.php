<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * (c) Melech Mizrachi <melechmizrachi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Tests\Classes\Cli\Server\Data;

use Valkyrja\Cli\Server\Data\Contract\CliHelpCommandConfigContract;
use Valkyrja\Cli\Server\Data\Contract\CliNoInteractionConfigContract;
use Valkyrja\Cli\Server\Data\Contract\CliQuietInteractionConfigContract;
use Valkyrja\Cli\Server\Data\Contract\CliSilentInteractionConfigContract;
use Valkyrja\Cli\Server\Data\Contract\CliVersionCommandConfigContract;

final class CliCommandCommandConfigClass implements CliHelpCommandConfigContract, CliVersionCommandConfigContract, CliNoInteractionConfigContract, CliQuietInteractionConfigContract, CliSilentInteractionConfigContract
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
