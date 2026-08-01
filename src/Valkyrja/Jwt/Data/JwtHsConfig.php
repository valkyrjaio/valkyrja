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

namespace Valkyrja\Jwt\Data;

use Valkyrja\Jwt\Data\Contract\JwtHsConfigContract;

class JwtHsConfig implements JwtHsConfigContract
{
    /**
     * @param non-empty-string $hsKey The shared key that signs and verifies a token
     */
    public function __construct(
        public readonly string $hsKey = 'key',
    ) {
    }
}
