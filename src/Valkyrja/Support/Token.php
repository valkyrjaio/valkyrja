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

namespace Valkyrja\Support;

use Exception;

use function base64_encode;
use function random_bytes;
use function trim;

/**
 * class Token.
 *
 * @author Melech Mizrachi
 */
class Token
{
    /**
     * Get a token.
     *
     * @param int $length
     *
     * @throws Exception
     *
     * @return string
     */
    public static function getToken(int $length = 20): string
    {
        return trim(base64_encode(random_bytes($length)), " \t\n\r\0\x0B/");
    }
}
