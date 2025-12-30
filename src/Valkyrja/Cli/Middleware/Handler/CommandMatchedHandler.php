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
use Valkyrja\Cli\Middleware\Contract\CommandMatchedMiddleware;
use Valkyrja\Cli\Middleware\Handler\Abstract\Handler;
use Valkyrja\Cli\Middleware\Handler\Contract\CommandMatchedHandler as Contract;
use Valkyrja\Cli\Routing\Data\Contract\Route;

/**
 * Class CommandMatchedHandler.
 *
 * @author Melech Mizrachi
 *
 * @extends Handler<CommandMatchedMiddleware>
 */
class CommandMatchedHandler extends Handler implements Contract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function commandMatched(Input $input, Route $command): Route|Output
    {
        $next = $this->next;

        return $next !== null
            ? $this->getMiddleware($next)->commandMatched($input, $command, $this)
            : $command;
    }
}
