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

namespace Valkyrja\Cli\Middleware\Handler;

use Override;
use Valkyrja\Cli\Interaction\Input\Contract\Input;
use Valkyrja\Cli\Interaction\Output\Contract\Output;
use Valkyrja\Cli\Middleware\Contract\CommandDispatchedMiddleware;
use Valkyrja\Cli\Routing\Data\Contract\Route;

/**
 * Class DispatchedHandler.
 *
 * @author Melech Mizrachi
 *
 * @extends Handler<CommandDispatchedMiddleware>
 */
class CommandDispatchedHandler extends Handler implements Contract\CommandDispatchedHandler
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function commandDispatched(Input $input, Output $output, Route $command): Output
    {
        $next = $this->next;

        return $next !== null
            ? $this->getMiddleware($next)->commandDispatched($input, $output, $command, $this)
            : $output;
    }
}
