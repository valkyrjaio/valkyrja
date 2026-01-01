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
use Valkyrja\Cli\Middleware\Contract\ExitedMiddlewareContract;
use Valkyrja\Cli\Middleware\Handler\Contract\ExitedHandlerContract;
use Valkyrja\Tests\Classes\Cli\Middleware\Trait\MiddlewareCounterTrait;

/**
 * Class TestExitedMiddlewareChanged.
 */
class ExitedMiddlewareChangedClass implements ExitedMiddlewareContract
{
    use MiddlewareCounterTrait;

    public function exited(InputContract $input, OutputContract $output, ExitedHandlerContract $handler): void
    {
        $this->updateCounter();
        // Don't call the handler to simulate early exit
    }
}
