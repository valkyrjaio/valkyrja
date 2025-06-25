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

/**
 * Interface ExitedHandler.
 *
 * @author Melech Mizrachi
 */
interface ExitedHandler
{
    /**
     * Middleware handler ran when the application has exited.
     */
    public function exited(Input $input, Output $output): void;
}
