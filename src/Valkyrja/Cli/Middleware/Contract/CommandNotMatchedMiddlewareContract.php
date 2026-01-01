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

use Valkyrja\Cli\Interaction\Input\Contract\InputContract;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Middleware\Handler\Contract\CommandNotMatchedHandlerContract;

/**
 * Interface CommandNotMatchedMiddlewareContract.
 *
 * @author Melech Mizrachi
 */
interface CommandNotMatchedMiddlewareContract
{
    /**
     * Middleware handler for after a command has not been matched.
     */
    public function commandNotMatched(InputContract $input, OutputContract $output, CommandNotMatchedHandlerContract $handler): OutputContract;
}
