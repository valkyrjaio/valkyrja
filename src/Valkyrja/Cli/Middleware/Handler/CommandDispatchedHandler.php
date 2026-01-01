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
use Valkyrja\Cli\Interaction\Input\Contract\InputContract;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Middleware\Contract\CommandDispatchedMiddlewareContract;
use Valkyrja\Cli\Middleware\Handler\Abstract\Handler;
use Valkyrja\Cli\Middleware\Handler\Contract\CommandDispatchedHandlerContract as Contract;
use Valkyrja\Cli\Routing\Data\Contract\RouteContract;

/**
 * @extends Handler<CommandDispatchedMiddlewareContract>
 */
class CommandDispatchedHandler extends Handler implements Contract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function commandDispatched(InputContract $input, OutputContract $output, RouteContract $command): OutputContract
    {
        $next = $this->next;

        return $next !== null
            ? $this->getMiddleware($next)->commandDispatched($input, $output, $command, $this)
            : $output;
    }
}
