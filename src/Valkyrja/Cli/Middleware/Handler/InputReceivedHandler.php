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
use Valkyrja\Cli\Middleware\Contract\InputReceivedMiddlewareContract;
use Valkyrja\Cli\Middleware\Handler\Abstract\Handler;
use Valkyrja\Cli\Middleware\Handler\Contract\InputReceivedHandlerContract as Contract;

/**
 * Class InputReceivedHandler.
 *
 * @author Melech Mizrachi
 *
 * @extends Handler<InputReceivedMiddlewareContract>
 */
class InputReceivedHandler extends Handler implements Contract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function inputReceived(InputContract $input): InputContract|OutputContract
    {
        $next = $this->next;

        return $next !== null
            ? $this->getMiddleware($next)->inputReceived($input, $this)
            : $input;
    }
}
