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

namespace Valkyrja\Cli\Middleware\Contract;

use Valkyrja\Cli\Interaction\Input\Contract\Input;
use Valkyrja\Cli\Interaction\Output\Contract\Output;
use Valkyrja\Cli\Middleware\Handler\Contract\InputReceivedHandler;

/**
 * Interface InputReceivedMiddleware.
 *
 * @author Melech Mizrachi
 */
interface InputReceivedMiddleware
{
    /**
     * Middleware handler for an input received.
     */
    public function inputReceived(Input $input, InputReceivedHandler $handler): Input|Output;
}
