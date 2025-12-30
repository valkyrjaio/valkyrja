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
use Valkyrja\Cli\Middleware\Contract\CommandNotMatchedMiddleware;
use Valkyrja\Cli\Middleware\Handler\Contract\CommandNotMatchedHandler as Contract;

/**
 * Class CommandNotMatchedHandler.
 *
 * @author Melech Mizrachi
 *
 * @extends Handler<CommandNotMatchedMiddleware>
 */
class CommandNotMatchedHandler extends Handler implements Contract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function commandNotMatched(Input $input, Output $output): Output
    {
        $next = $this->next;

        return $next !== null
            ? $this->getMiddleware($next)->commandNotMatched($input, $output, $this)
            : $output;
    }
}
