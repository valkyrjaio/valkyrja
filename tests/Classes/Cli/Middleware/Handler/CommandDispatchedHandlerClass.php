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

namespace Valkyrja\Tests\Classes\Cli\Middleware\Handler;

use Override;
use Valkyrja\Cli\Interaction\Input\Contract\InputContract;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Middleware\Handler\CommandDispatchedHandler;
use Valkyrja\Cli\Routing\Data\Contract\RouteContract;

/**
 * Class TestCommandDispatchedHandler.
 *
 * @author Melech Mizrachi
 */
class CommandDispatchedHandlerClass extends CommandDispatchedHandler
{
    protected int $count = 0;

    /**
     * Get the count of calls.
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function commandDispatched(InputContract $input, OutputContract $output, RouteContract $command): OutputContract
    {
        $this->count++;

        return parent::commandDispatched($input, $output, $command);
    }
}
