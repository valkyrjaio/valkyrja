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

namespace Valkyrja\Cli\Middleware\Handler\Contract;

use Valkyrja\Cli\Interaction\Input\Contract\Input;
use Valkyrja\Cli\Interaction\Output\Contract\Output;
use Valkyrja\Cli\Routing\Data\Contract\Command;

/**
 * Interface CommandMatchedHandler.
 *
 * @author Melech Mizrachi
 */
interface CommandMatchedHandler
{
    /**
     * Middleware handler for after a command has been matched but before it has been dispatched.
     */
    public function commandMatched(Input $input, Command $command): Command|Output;
}
