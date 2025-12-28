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

namespace Valkyrja\Cli\Routing\Dispatcher\Contract;

use Valkyrja\Cli\Interaction\Input\Contract\Input;
use Valkyrja\Cli\Interaction\Output\Contract\Output;
use Valkyrja\Cli\Routing\Data\Contract\Route;

/**
 * Interface Router.
 *
 * @author Melech Mizrachi
 */
interface Router
{
    /**
     * Dispatch an input and return an output.
     */
    public function dispatch(Input $input): Output;

    /**
     * Dispatch an input for a specific command.
     */
    public function dispatchCommand(Input $input, Route $command): Output;
}
