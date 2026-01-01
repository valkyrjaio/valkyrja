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

namespace Valkyrja\Cli\Interaction\Data;

/**
 * Class Config.
 */
class Config
{
    public function __construct(
        public bool $isQuiet = false,
        public bool $isInteractive = true,
        public bool $isSilent = false,
    ) {
    }
}
