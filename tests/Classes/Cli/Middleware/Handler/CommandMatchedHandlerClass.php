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
use Valkyrja\Cli\Middleware\Handler\CommandMatchedHandler;
use Valkyrja\Cli\Routing\Data\Contract\RouteContract;

/**
 * Class TestCommandMatchedHandler.
 *
 * @author Melech Mizrachi
 */
class CommandMatchedHandlerClass extends CommandMatchedHandler
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
    public function commandMatched(InputContract $input, RouteContract $command): RouteContract|OutputContract
    {
        $this->count++;

        return parent::commandMatched($input, $command);
    }
}
