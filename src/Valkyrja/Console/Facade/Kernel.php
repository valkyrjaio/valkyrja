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

namespace Valkyrja\Console\Facade;

use Valkyrja\Console\Input\Contract\Input as ConsoleInput;
use Valkyrja\Console\Kernel\Contract\Kernel as Contract;
use Valkyrja\Console\Output\Contract\Output as ConsoleOutput;
use Valkyrja\Facade\ContainerFacade;

/**
 * Class Kernel.
 *
 * @author Melech Mizrachi
 *
 * @method static int  handle(ConsoleInput $input, ConsoleOutput $output)
 * @method static void terminate(ConsoleInput $input, int $exitCode)
 * @method static int  run(ConsoleInput $input = null, ConsoleOutput $output = null)
 */
class Kernel extends ContainerFacade
{
    /**
     * @inheritDoc
     */
    public static function instance(): object
    {
        return self::getContainer()->getSingleton(Contract::class);
    }
}
