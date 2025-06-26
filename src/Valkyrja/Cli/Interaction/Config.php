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

namespace Valkyrja\Cli\Interaction;

use Valkyrja\Cli\Interaction\Constant\ConfigName;
use Valkyrja\Cli\Interaction\Constant\EnvName;
use Valkyrja\Config\Config as ParentConfig;

/**
 * Class Config.
 *
 * @author Melech Mizrachi
 */
class Config extends ParentConfig
{
    /**
     * @inheritDoc
     *
     * @var array<string, string>
     */
    protected static array $envNames = [
        ConfigName::IS_QUIET       => EnvName::IS_QUIET,
        ConfigName::IS_INTERACTIVE => EnvName::IS_INTERACTIVE,
        ConfigName::IS_SILENT      => EnvName::IS_SILENT,
    ];

    public function __construct(
        public bool $isQuiet = false,
        public bool $isInteractive = true,
        public bool $isSilent = false,
    ) {
    }
}
