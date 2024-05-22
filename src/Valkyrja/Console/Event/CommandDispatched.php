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

namespace Valkyrja\Console\Event;

use Valkyrja\Console\Model\Contract\Command;

/**
 * Class CommandDispatched.
 *
 * @author Melech Mizrachi
 */
class CommandDispatched
{
    public function __construct(
        public Command $command,
        public int $exitCode
    ) {
    }
}
