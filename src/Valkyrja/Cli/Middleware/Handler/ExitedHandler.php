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
use Valkyrja\Cli\Middleware\Contract\ExitedMiddlewareContract;
use Valkyrja\Cli\Middleware\Handler\Abstract\Handler;
use Valkyrja\Cli\Middleware\Handler\Contract\ExitedHandlerContract as Contract;

/**
 * Class ExitedHandler.
 *
 * @author Melech Mizrachi
 *
 * @extends Handler<ExitedMiddlewareContract>
 */
class ExitedHandler extends Handler implements Contract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function exited(InputContract $input, OutputContract $output): void
    {
        $next = $this->next;

        if ($next !== null) {
            $this->getMiddleware($next)->exited($input, $output, $this);
        }
    }
}
