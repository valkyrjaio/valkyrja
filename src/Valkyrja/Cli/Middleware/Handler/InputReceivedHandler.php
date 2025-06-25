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

use Valkyrja\Cli\Interaction\Input\Contract\Input;
use Valkyrja\Cli\Interaction\Output\Contract\Output;
use Valkyrja\Cli\Middleware\Contract\InputReceivedMiddleware;

/**
 * Class InputReceivedHandler.
 *
 * @author Melech Mizrachi
 *
 * @extends Handler<InputReceivedMiddleware>
 */
class InputReceivedHandler extends Handler implements Contract\InputReceivedHandler
{
    /**
     * @inheritDoc
     */
    public function inputReceived(Input $input): Input|Output
    {
        $next = $this->next;

        return $next !== null
            ? $this->getMiddleware($next)->inputReceived($input, $this)
            : $input;
    }
}
