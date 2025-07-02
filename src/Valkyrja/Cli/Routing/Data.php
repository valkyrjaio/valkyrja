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

namespace Valkyrja\Cli\Routing;

/**
 * Class Data.
 *
 * @author Melech Mizrachi
 */
readonly class Data
{
    /**
     * @param array<string, string> $commands
     */
    public function __construct(
        public array $commands = []
    ) {
    }
}
