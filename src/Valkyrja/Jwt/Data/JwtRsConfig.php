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
