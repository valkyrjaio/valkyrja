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
use Valkyrja\Cli\Middleware\Contract\ExitedMiddleware;

/**
 * Class ExitedHandler.
 *
 * @author Melech Mizrachi
 *
 * @extends Handler<ExitedMiddleware>
 */
class ExitedHandler extends Handler implements Contract\ExitedHandler
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function exited(Input $input, Output $output): void
    {
        $next = $this->next;

        if ($next !== null) {
            $this->getMiddleware($next)->exited($input, $output, $this);
        }
    }
}
