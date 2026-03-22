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

use Valkyrja\Cli\Server\Data\Contract\HelpConfigContract;
use Valkyrja\Cli\Server\Data\Contract\NoInteractionConfigContract;
use Valkyrja\Cli\Server\Data\Contract\QuietInteractionConfigContract;
use Valkyrja\Cli\Server\Data\Contract\SilentInteractionConfigContract;
use Valkyrja\Cli\Server\Data\Contract\VersionConfigContract;

final class ConfigClass implements HelpConfigContract, VersionConfigContract, NoInteractionConfigContract, QuietInteractionConfigContract, SilentInteractionConfigContract
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
