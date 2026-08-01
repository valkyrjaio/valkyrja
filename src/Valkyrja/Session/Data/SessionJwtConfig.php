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

namespace Valkyrja\Session\Data;

use Valkyrja\Session\Data\Contract\SessionJwtConfigContract;

class SessionJwtConfig implements SessionJwtConfigContract
{
    /**
     * @param non-empty-string|null $jwtOptionName The cli option that carries the token
     * @param non-empty-string|null $jwtHeaderName The http header that carries the token
     */
    public function __construct(
        public readonly string|null $jwtOptionName = null,
        public readonly string|null $jwtHeaderName = null,
    ) {
    }
}
