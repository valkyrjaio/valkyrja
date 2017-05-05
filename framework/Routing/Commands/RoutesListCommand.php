<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Routing\Commands;

use Valkyrja\Console\CommandHandler;

/**
 * Class RoutesListCommand
 *
 * @package Valkyrja\Routing\Commands
 *
 * @author  Melech Mizrachi
 */
class RoutesListCommand extends CommandHandler
{
    /**
     * The command.
     */
    public const COMMAND           = 'routes:cache';
    public const SHORT_DESCRIPTION = 'List all routes';
    public const DESCRIPTION       = '';

    /**
     * Run the command.
     *
     * @return int
     */
    public function run(): int
    {
        // TODO: Rethink how routes are stored.

        return 1;
    }
}
