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

use Valkyrja\Jwt\Data\Contract\JwtRsConfigContract;

class JwtRsConfig implements JwtRsConfigContract
{
    /**
     * @param non-empty-string $rsPrivateKey The private key that signs a token
     * @param non-empty-string $rsPublicKey  The public key that verifies a token
     */
    public function __construct(
        public readonly string $rsPrivateKey = 'private-key',
        public readonly string $rsPublicKey = 'public-key',
    ) {
    }
}
