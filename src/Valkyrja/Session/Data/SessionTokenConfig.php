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

use Valkyrja\Session\Data\Contract\SessionTokenConfigContract;

class SessionTokenConfig implements SessionTokenConfigContract
{
    /**
     * @param non-empty-string|null $tokenOptionName The cli option that carries the token
     * @param non-empty-string|null $tokenHeaderName The http header that carries the token
     */
    public function __construct(
        public readonly string|null $tokenOptionName = null,
        public readonly string|null $tokenHeaderName = null,
    ) {
    }
}
