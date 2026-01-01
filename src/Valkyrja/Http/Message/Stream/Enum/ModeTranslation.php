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
 * @see https://www.php.net/manual/en/function.fopen.php
 */
enum ModeTranslation: string
{
    case NONE        = '';
    case WINDOWS     = 't';
    case BINARY_SAFE = 'b';
}
