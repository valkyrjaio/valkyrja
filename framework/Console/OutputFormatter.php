<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Console;

use Valkyrja\Contracts\Console\OutputFormatter as OutputFormatterContract;

/**
 * Class OutputFormatter
 *
 * @package Valkyrja\Console
 *
 * @author  Melech Mizrachi
 */
class OutputFormatter implements OutputFormatterContract
{
    /**
     * Format a message.
     *
     * @param string $message The message
     *
     * @return string
     */
    public function format(string $message): string
    {
        return $message;
    }
}
