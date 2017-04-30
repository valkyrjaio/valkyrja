<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Contracts\Console;

/**
 * Interface OutputFormatter
 *
 * @package Valkyrja\Contracts\Console
 *
 * @author  Melech Mizrachi
 */
interface OutputFormatter
{
    /**
     * Format a message.
     *
     * @param string $message The message
     *
     * @return string
     */
    public function format(string $message): string;
}
