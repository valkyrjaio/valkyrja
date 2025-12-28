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

use function in_array;

/**
 * Enum Mode.
 *
 * @author Melech Mizrachi
 *
 * @see    https://www.php.net/manual/en/function.fopen.php
 */
enum Mode: string
{
    case READ              = 'r';
    case READ_WRITE        = 'r+';
    case WRITE             = 'w';
    case WRITE_READ        = 'w+';
    case WRITE_END         = 'a';
    case WRITE_READ_END    = 'a+';
    case CREATE_WRITE      = 'x';
    case CREATE_WRITE_READ = 'x+';
    case WRITE_CREATE      = 'c';
    case WRITE_READ_CREATE = 'c+';
    case CLOSE_ON_EXEC     = 'e';

    public function isReadable(): bool
    {
        return in_array(
            $this,
            [
                self::READ,
                self::READ_WRITE,
                self::WRITE_READ,
                self::WRITE_READ_END,
                self::CREATE_WRITE_READ,
                self::WRITE_READ_CREATE,
            ],
            true
        );
    }

    public function isWriteable(): bool
    {
        return in_array(
            $this,
            [
                self::READ_WRITE,
                self::WRITE,
                self::WRITE_READ,
                self::WRITE_END,
                self::WRITE_READ_END,
                self::CREATE_WRITE,
                self::CREATE_WRITE_READ,
                self::WRITE_CREATE,
                self::WRITE_READ_CREATE,
            ],
            true
        );
    }
}
