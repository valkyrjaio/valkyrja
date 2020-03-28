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

namespace Valkyrja\Console\Facades;

use Valkyrja\Application\Applications\Valkyrja;
use Valkyrja\Console\Command;
use Valkyrja\Console\Input as ConsoleInput;
use Valkyrja\Console\Output as ConsoleOutput;
use Valkyrja\Facade\Facades\Facade;

/**
 * Class Console.
 *
 * @author Melech Mizrachi
 *
 * @method static void addCommand(Command $command)
 * @method static Command|null command(string $name)
 * @method static bool hasCommand(string $name)
 * @method static void removeCommand(string $name)
 * @method static Command inputCommand(ConsoleInput $input)
 * @method static Command matchCommand(string $path)
 * @method static int dispatch(ConsoleInput $input, ConsoleOutput $output)
 * @method static int dispatchCommand(Command $command)
 * @method static Command[] all()
 * @method static void set(Command ...$commands)
 * @method static string[] getNamedCommands()
 */
class Console extends Facade
{
    /**
     * The facade instance.
     *
     * @return string|object
     */
    public static function instance()
    {
        return Valkyrja::app()->console();
    }
}
