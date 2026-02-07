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

namespace Valkyrja\Tests\Classes\Cli\Middleware;

use Valkyrja\Cli\Interaction\Input\Contract\InputContract;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Interaction\Output\Output;
use Valkyrja\Cli\Middleware\Contract\InputReceivedMiddlewareContract;
use Valkyrja\Cli\Middleware\Handler\Contract\InputReceivedHandlerContract;
use Valkyrja\Tests\Classes\Cli\Middleware\Trait\MiddlewareCounterTrait;

/**
 * Class TestInputReceivedMiddlewareChanged.
 */
final class InputReceivedMiddlewareChangedClass implements InputReceivedMiddlewareContract
{
    use MiddlewareCounterTrait;

    public function inputReceived(InputContract $input, InputReceivedHandlerContract $handler): InputContract|OutputContract
    {
        $this->updateCounter();

        // Return an output instead of calling the handler to simulate early exit
        return new Output();
    }
}
