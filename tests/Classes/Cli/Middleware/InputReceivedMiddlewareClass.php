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

use Valkyrja\Cli\Interaction\Input\Contract\Input;
use Valkyrja\Cli\Interaction\Output\Contract\Output;
use Valkyrja\Cli\Middleware\Contract\InputReceivedMiddleware;
use Valkyrja\Cli\Middleware\Handler\Contract\InputReceivedHandler;
use Valkyrja\Tests\Classes\Cli\Middleware\Trait\MiddlewareCounterTrait;

/**
 * Class TestInputReceivedMiddleware.
 *
 * @author Melech Mizrachi
 */
class InputReceivedMiddlewareClass implements InputReceivedMiddleware
{
    use MiddlewareCounterTrait;

    public function inputReceived(Input $input, InputReceivedHandler $handler): Input|Output
    {
        $this->updateCounter();

        return $handler->inputReceived($input);
    }
}
