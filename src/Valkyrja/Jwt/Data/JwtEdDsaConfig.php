<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
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
