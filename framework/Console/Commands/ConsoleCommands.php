<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Console\Commands;

use Valkyrja\Console\Command;
use Valkyrja\Console\CommandHandler;

/**
 * Class ConsoleCommands
 *
 * @package Valkyrja\Console\Commands
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
        $list = console()->getCacheable()['commands'];
        $longestLength = 0;

        usort($list, function (Command $item1, Command $item2) {
            return $item1->getName() <=> $item2->getName();
        });

        /** @var \Valkyrja\Console\Command $item */
        foreach ($list as $item) {
            if ($longestLength < $nameLength = strlen($item->getName())) {
                $longestLength = $nameLength;
            }
        }

        /** @var \Valkyrja\Console\Command $item */
        foreach ($list as $item) {
            $spacesToAdd = $longestLength - strlen($item->getName());
            $name = $item->getName() . ($spacesToAdd > 0 ? str_repeat(' ', $spacesToAdd) : '');

            output()->writeMessage("\033[35m{$name}\033[39m\t\t{$item->getDescription()}", true);
        }

        return 1;
    }
}
