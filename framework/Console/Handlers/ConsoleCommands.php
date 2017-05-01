<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Console\Handlers;

use Valkyrja\Console\CommandHandler;

/**
 * Class ConsoleCommands
 *
 * @package Valkyrja\Console\Handlers
 *
 * @author  Melech Mizrachi
 */
class ConsoleCommands extends CommandHandler
{
    /**
     * The command.
     */
    public const COMMAND = 'console:commands';

    /**
     * Run the command.
     *
     * @return int
     */
    public function run(): int
    {
        $list = console()->getCacheable();

        /** @var \Valkyrja\Console\Command $item */
        foreach ($list['commands'] as $item) {
            output()->writeMessage("{$item->getName()}      {$item->getDescription()}", true);
        }

        return 1;
    }
}
