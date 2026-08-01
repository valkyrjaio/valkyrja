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

use Valkyrja\Jwt\Data\Contract\JwtEdDsaConfigContract;

class JwtEdDsaConfig implements JwtEdDsaConfigContract
{
    /**
     * @param non-empty-string $edDsaPrivateKey The private key that signs a token
     * @param non-empty-string $edDsaPublicKey  The public key that verifies a token
     */
    public function __construct(
        public readonly string $edDsaPrivateKey = 'private-key',
        public readonly string $edDsaPublicKey = 'public-key',
    ) {
    }
}
