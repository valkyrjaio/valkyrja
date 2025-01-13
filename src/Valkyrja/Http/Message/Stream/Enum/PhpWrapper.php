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

namespace Valkyrja\Http\Message\Stream\Enum;

/**
 * Enum PhpWrapper.
 *
 * @author Melech Mizrachi
 *
 * @see    https://www.php.net/manual/en/function.fopen.php
 */
enum PhpWrapper: string
{
    case stdin  = 'php://stdin';
    case stdout = 'php://stdout';
    case stderr = 'php://stderr';
    case input  = 'php://input';
    case output = 'php://output';
    case fd     = 'php://fd';
    case memory = 'php://memory';
    case temp   = 'php://temp';
    case filter = 'php://filter';
}
